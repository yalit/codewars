import {Parser, Query, SQLEngine} from "../../src/kyu3/SQLEngine";

describe('query', function () {
    const selects = ['table.column'];
    const from = 'table';
    it('should create an empty Query', function () {
        const query = new Query(selects, from);
        expect(query.selects).toEqual(selects);
        expect(query.from).toEqual(from);
        expect(query.joins).toEqual([]);
        expect(query.where).toEqual({});
    })

    it('should return false when there is no where clause', function () {
        const query = new Query(selects, from);
        expect(query.hasWhere()).toBe(false);
    })

    it('should return false when there is no join clause', function () {
        const query = new Query(selects, from);
        expect(query.hasJoin()).toBe(false);
    })

    it('should return false when there is an empty where clause', function () {
        const query = new Query(selects, from);
        query.where = {};
        expect(query.hasWhere()).toBe(false);
    })

    it('should return false when there is an incomplete where clause', function () {
        const query = new Query(selects, from);
        query.where = {column: 'columName', operator: '='};
        expect(query.hasWhere()).toBe(false);
    })

    it('should return true when there is a where clause', function () {
        const query = new Query(selects, from);
        query.where = {column: 'columName', operator: '=', value: 1};
        expect(query.hasWhere()).toBe(true);
    })
})

describe('parser', function () {
    it('should parse simple select and from', function () {
        const parser = new Parser('SELECT table.column FROM table');
        const query = parser.parse();
        expect(query).toBeInstanceOf(Query);
        expect(query.selects).toEqual(['table.column']);
        expect(query.from).toEqual('table');
    })

    it('should parse multiple selects', function () {
        const parser = new Parser('SELECT table.column, table.column2 FROM table');
        const query = parser.parse();
        expect(query).toBeInstanceOf(Query);
        expect(query.selects).toEqual(['table.column', 'table.column2']);
        expect(query.from).toEqual('table');
    })

    it('should parse select with spaces', function () {
        const parser = new Parser('SELECT table.column ,  table.column2 FROM table');
        const query = parser.parse();
        expect(query).toBeInstanceOf(Query);
        expect(query.selects).toEqual(['table.column', 'table.column2']);
        expect(query.from).toEqual('table');
    })

    it('should parse "=" where clause', function () {
        const parser = new Parser('SELECT table.column FROM table WHERE table.column = 1');
        const query = parser.parse();
        expect(query.where).toEqual({column: 'table.column', operator: '=', value: 1});
    })

    it('should parse "<" where clause', function () {
        const parser = new Parser('SELECT table.column FROM table WHERE table.column < 1');
        const query = parser.parse();
        expect(query.where).toEqual({column: 'table.column', operator: '<', value: 1});
    })

    it('should parse ">" where clause', function () {
        const parser = new Parser('SELECT table.column FROM table WHERE table.column > 1');
        const query = parser.parse();
        expect(query.where).toEqual({column: 'table.column', operator: '>', value: 1});
    })

    it('should parse "<=" where clause', function () {
        const parser = new Parser('SELECT table.column FROM table WHERE table.column <= 1');
        const query = parser.parse();
        expect(query.where).toEqual({column: 'table.column', operator: '<=', value: 1});
    })

    it('should parse ">=" where clause', function () {
        const parser = new Parser('SELECT table.column FROM table WHERE table.column >= 1');
        const query = parser.parse();
        expect(query.where).toEqual({column: 'table.column', operator: '>=', value: 1});
    })

    it('should parse "<>" where clause', function () {
        const parser = new Parser('SELECT table.column FROM table WHERE table.column <> 1');
        const query = parser.parse();
        expect(query.where).toEqual({column: 'table.column', operator: '<>', value: 1});
    })

    it('should parse complex string in where clause', function () {
        const parser = new Parser('SELECT table.column FROM table WHERE table.column = "string"');
        const query = parser.parse();
        expect(query.where).toEqual({column: 'table.column', operator: '=', value: 'string'});
    })

    it('should parse string containing escaped characters in where clause', function () {
        const parser = new Parser('SELECT table.column FROM table WHERE table.column = "str\\"ing"');
        const query = parser.parse();
        expect(query.where).toEqual({column: 'table.column', operator: '=', value: 'str"ing'});
    })

    it('should parse string with \' not escaped in where clause', function () {
        const parser = new Parser("SELECT table.column FROM table WHERE table.column = 'string'");
        const query = parser.parse();
        expect(query.where).toEqual({column: 'table.column', operator: '=', value: 'string'});
    })

    it('should parse single join', function () {
        const parser = new Parser('SELECT table.column FROM table JOIN table2 ON table.column1 = table2.column2');
        const query = parser.parse();
        expect(query.joins).toEqual([{table: 'table2', on: ['table.column1', 'table2.column2']}]);
    })

    it('should parse multiple joins', function () {
        const parser = new Parser('SELECT table.column FROM table JOIN table2 ON table.column1 = table2.column2 JOIN table3 ON table.column1 = table3.column3');
        const query = parser.parse();
        expect(query.joins).toEqual([{table: 'table2', on: ['table.column1', 'table2.column2']}, {
            table: 'table3',
            on: ['table.column1', 'table3.column3']
        }]);
    })
})

describe('SQLEngine', function () {
    it('should create a new SQLEngine', function () {
        const engine = new SQLEngine(movieDatabase);
        expect(engine).toBeInstanceOf(SQLEngine);
    })

    it('should SELECT columns', function () {
        const engine = new SQLEngine(movieDatabase);
        const actual = engine.execute('SELECT movie.name FROM movie');
        assertSimilarRows(actual,[{'movie.name': 'Avatar'},
            {'movie.name': 'Titanic'},
            {'movie.name': 'Infamous'},
            {'movie.name': 'Skyfall'},
            {'movie.name': 'Aliens'}]
        )
    })

    it('should apply WHERE', function () {
        const engine = new SQLEngine(movieDatabase);
        const actual = engine.execute('SELECT movie.name FROM movie WHERE movie.directorID = 1');
        assertSimilarRows(actual,[{'movie.name': 'Avatar'},
            {'movie.name': 'Titanic'},
            {'movie.name': 'Aliens'}]);
    })

    it('should perform parent->child JOIN', function () {
        const engine = new SQLEngine(movieDatabase);
        const actual = engine.execute('SELECT movie.name, director.name '
            + 'FROM movie '
            + 'JOIN director ON director.id = movie.directorID');
        assertSimilarRows(actual,[{'movie.name': 'Avatar', 'director.name': 'James Cameron'},
            {'movie.name': 'Titanic', 'director.name': 'James Cameron'},
            {'movie.name': 'Aliens', 'director.name': 'James Cameron'},
            {'movie.name': 'Infamous', 'director.name': 'Douglas McGrath'},
            {'movie.name': 'Skyfall', 'director.name': 'Sam Mendes'}]);
    })

    it('should perform child->parent JOIN ', function () {
        const engine = new SQLEngine(movieDatabase);
        const actual = engine.execute('SELECT movie.name, director.name '
            + 'FROM director '
            + 'JOIN movie ON director.id = movie.directorID');
        assertSimilarRows(actual,[{'movie.name': 'Avatar', 'director.name': 'James Cameron'},
            {'movie.name': 'Titanic', 'director.name': 'James Cameron'},
            {'movie.name': 'Infamous', 'director.name': 'Douglas McGrath'},
            {'movie.name': 'Skyfall', 'director.name': 'Sam Mendes'},
            {'movie.name': 'Aliens', 'director.name': 'James Cameron'}]);
    })

    it('should perform many-to-many JOIN', function () {
        const engine = new SQLEngine(movieDatabase);
        const actual = engine.execute('SELECT movie.name, actor.name '
            + 'FROM movie '
            + 'JOIN actor_to_movie ON movie.id = actor_to_movie.movieID '
            + 'JOIN actor ON actor.id = actor_to_movie.actorID');
        assertSimilarRows(actual,[{'movie.name': 'Avatar', 'actor.name': 'Sigourney Weaver'},
            {'movie.name': 'Titanic', 'actor.name': 'Leonardo DiCaprio'},
            {'movie.name': 'Infamous', 'actor.name': 'Sigourney Weaver'},
            {'movie.name': 'Infamous', 'actor.name': 'Daniel Craig'},
            {'movie.name': 'Skyfall', 'actor.name': 'Daniel Craig'},
            {'movie.name': 'Aliens', 'actor.name': 'Sigourney Weaver'}]);
    })

    it('should perform many-to-many JOIN and apply WHERE', function(){
        const engine = new SQLEngine(movieDatabase);
        const actual = engine.execute('SELECT movie.name, actor.name '
            +'FROM movie '
            +'JOIN actor_to_movie ON actor_to_movie.movieID = movie.id '
            +'JOIN actor ON actor_to_movie.actorID = actor.id '
            +'WHERE actor.name <> \'Daniel Craig\'');
        assertSimilarRows(actual, [{'movie.name':'Aliens','actor.name':'Sigourney Weaver'},
            {'movie.name':'Avatar','actor.name':'Sigourney Weaver'},
            {'movie.name':'Infamous','actor.name':'Sigourney Weaver'},
            {'movie.name':'Titanic','actor.name':'Leonardo DiCaprio'}]);
    });

    it('should perform where with escaped characters', function () {
        const engine = new SQLEngine(escapedMovieNameDatabase);
        const actual = engine.execute('SELECT movie.name FROM movie WHERE movie.name = "Pirates of the Caribbean: Dead Man\'s Chest"');
        assertSimilarRows(actual, [{'movie.name': "Pirates of the Caribbean: Dead Man's Chest"}]);
    })
})

const movieDatabase = {
    movie: [
        {id: 1, name: 'Avatar', directorID: 1},
        {id: 2, name: 'Titanic', directorID: 1},
        {id: 3, name: 'Infamous', directorID: 2},
        {id: 4, name: 'Skyfall', directorID: 3},
        {id: 5, name: 'Aliens', directorID: 1}
    ],
    actor: [
        {id: 1, name: 'Leonardo DiCaprio'},
        {id: 2, name: 'Sigourney Weaver'},
        {id: 3, name: 'Daniel Craig'},
    ],
    director: [
        {id: 1, name: 'James Cameron'},
        {id: 2, name: 'Douglas McGrath'},
        {id: 3, name: 'Sam Mendes'}
    ],
    actor_to_movie: [
        {movieID: 1, actorID: 2},
        {movieID: 2, actorID: 1},
        {movieID: 3, actorID: 2},
        {movieID: 3, actorID: 3},
        {movieID: 4, actorID: 3},
        {movieID: 5, actorID: 2},
    ]
};

const escapedMovieNameDatabase = {
    movie: [
        {id: 1, name: "Pirates of the Caribbean: Dead Man's Chest"}
        ]
}

function assertSimilarRows(actual, expected) {
    expect(actual.length).toEqual(expected.length);
    expected.forEach(function (row) {
        let found = false;
        actual.forEach(function (actualRow) {
            if (JSON.stringify(actualRow) === JSON.stringify(row)) {
                found = true;
            }
        })
        expect(found).toBeTruthy()
    });
}

