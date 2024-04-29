export function SQLEngine(database) {
    this.__database = {};

    Object.keys(database).forEach(k => {
        this.__database[k] = database[k].map(r => {
            const nr = {};
            Object.keys(r).forEach(c => {
                nr[k + '.' + c] = r[c];
            })
            return nr;
        })
    })

    this.execute = function(q){
        const parser = new Parser(q);
        const query = parser.parse();
        return this.__buildInternalTable(query)
    }

    /**
     *
     * @param query Query
     * @returns {*}[]
     * @private
     */
    this.__buildInternalTable = function(query) {
        let table = this.__database[query.from];
        if (query.hasJoin()) {
            table = this.__joinTables(table, query.joins);
        }
        if (query.hasWhere()) {
            table = this.__filterTable(table, query.where);
        }
        return this.__selectColumns(table, query.selects);
    }

    this.__selectColumns = function(table, selects) {
        return table.map(row => {
            const r = {};
            selects.forEach(s => {
                r[s] = row[s];
            })
            return r;
        })
    }

    this.__joinTables = function(table, joins) {
        let joinedTables = table.map(r => Object.assign({}, r));
        joins.forEach(j => {
            const joinedTable = this.__database[j.table];
            joinedTables.forEach(row => {
                const filteredToBeJoinedRows = joinedTable.filter(jr => {
                    const r = Object.assign(Object.assign({}, row), jr);
                    return r[j.on[0]] === r[j.on[1]];
                })

                filteredToBeJoinedRows.forEach((joinRow, k) => {
                    const newRow = Object.assign(Object.assign({}, row), joinRow)
                    if (newRow[j.on[0]] === newRow[j.on[1]]) {
                        if (k === 0) {
                            joinedTables[joinedTables.indexOf(row)] = newRow;
                        } else {
                            joinedTables.push(newRow);
                        }
                    }
                })
            })
        })
        return joinedTables;
    }

    this.__filterTable = function(table, where) {
        return table.filter(row => {
            return this.__applyWhere(row, where);
        })
    }

    this.__applyWhere = function(row, where) {
        const value = row[where.column];
        if (where.operator === '=') {
            return value === where.value;
        }
        if (where.operator === '<>') {
            return value !== where.value;
        }
        if (where.operator === '<') {
            return value < where.value;
        }
        if (where.operator === '>') {
            return value > where.value;
        }
        if (where.operator === '<=') {
            return value <= where.value;
        }
        if (where.operator === '>=') {
            return value >= where.value;
        }
    }
}


export class Parser {
    constructor (q) {
        this.q = q;
        this.selects = [];
        this.from = '';
        this.where = undefined;
        this.joins = [];
    }

    /**
     * @return Query
     */
    parse() {
        const selects = this.__parseSelect();
        const from = this.__parseFrom();

        let query = new Query(selects, from);
        if (this.__hasWhere()) {
            query.where = this.__parseWhere();
        }

        if (this.__hasJoin()) {
            query.joins = this.__parseJoin();
        }

        return query;
    }

    /**
     * @private
     * @return string[]
     */
    __parseSelect() {
        const r = /SELECT (.*) FROM/;
        const v = r.exec(this.q)

        return v[1].split(',').map(s => s.trim());
    }

    /**
     * @private
     * @return string
     */
   __parseFrom() {
        if (this.__hasJoin()) {
            return this.q.match(/FROM (.*?) JOIN/)[1].trim();
        }
        if (this.__hasWhere()) {
            return this.q.match(/FROM (.*) WHERE/)[1].trim();
        }
        return this.q.match(/FROM (.*)/)[1].trim();
    }


    /**
     * @private
     * @return (column: string, operator: string, value: int | string)
     */
    __parseWhere() {
        if (!this.__hasWhere()) {
            return undefined;
        }

        const getOperator = (s) => {
            if (s.includes('=')) {
                return '=';
            }
            if (s.includes('<>')) {
                return '<>';
            }
            if (s.includes('<=')) {
                return '<=';
            }
            if (s.includes('>=')) {
                return '>=';
            }
            if (s.includes('<')) {
                return '<';
            }
            if (s.includes('>')) {
                return '>';
            }
        }

        const r = /WHERE (.*)/;
        const v = r.exec(this.q);
        const operator = getOperator(v[1]);
        const w = v[1].split(operator);

        return {
            column: this.__cleanString(w[0]),
            operator: operator,
            value: isNumeric(w[1]) ? parseInt(w[1]) : this.__cleanString(w[1])
        }
    }

    /**
     *
     * @returns (table:string|on:string[])[]
     * @private
     */
    __parseJoin() {
        if (!this.__hasJoin()) {
            return undefined;
        }
        const r = this.__hasWhere() ? /JOIN (.*) WHERE/ : /JOIN (.*)/;
        const v = r.exec(this.q);
        const joins = v[1].split("JOIN");
        return joins.map(j => {
            return this.__parseJoinCondition(j);
        })
    }

    /**
     * @param j string
     * @returns (table: string, on: string[])
     * @private
     */
    __parseJoinCondition(j) {
        const join = j.trim().split("ON").map(s => this.__cleanString(s));
        const on = join[1].split("=");
        return {
            table: join[0],
            on: [String(on[0].trim()), String(on[1].trim())]
        }
    }

    __cleanString(s) {
        let cs = s.trim();
        if (cs[0] === "'" || cs[0] === '"') {
             cs = cs.slice(1, -1);
        }

        return cs.trim().replace(/\\'/g, "'").replace(/\\"/g, '"');
    }

    /**
     * @returns boolean
     * @private
     */
   __hasJoin() {
        return this.q.includes('JOIN');
    }

    /**
     * @returns boolean
     * @private
     */
    __hasWhere() {
        return this.q.includes('WHERE');
    }
}

export class Query {
    /**
     * @param selects string[]
     * @param from string
     */
    constructor(selects, from) {
        if (!(selects instanceof Array) || typeof from !== 'string' || selects.length === 0 || from === '') {
            throw new Error('Invalid query');
        }
        this.selects =  selects
        this.from = from;
        this.joins = [];
        // {column: string, operator: string, value: int | string}
        this.where = {};
    }
    hasWhere() {
        return this.where !== undefined
            && Object.keys(this.where).length === 3
            && Object.keys(this.where).every(k => ['column', 'operator', 'value'].includes(k));
    }
    hasJoin() {
        return this.joins.length > 0;
    }
}

function isNumeric(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
}
