describe("Router", function() {

  Router.getRootUrl = function() {
    return 'http://domain.tld';
  };

  Router.routes = [
    {"uri": "user\/{id}", "name": "user.show"},
    {"uri": "user", "name": "user.store"},
    {"uri": "user\/{id}\/edit", "name": "user.edit"},
    {"uri": "search", "name": "search.index"},
    {"uri": "search\/{type}", "name": "search.byType"},
    {"uri": "\/", "name": "home"},
    {"uri": "/cart\/add\/{article}\/{count?}", "name": "cart.add"},
  ];

  describe("When Router.route() it's called", function() {
    it('Should replace {params} in URI', function(done) {
      expect(Router.route('user.show', {id: 1})).toEqual('http://domain.tld/user/1');
    });

    it('Should replace {params} in URI', function(done) {
      expect(Router.route('user.edit', {id: 1})).toEqual('http://domain.tld/user/1/edit');
    });

    it('Should not replace wrong {params} in URI', function(done) {
      expect(Router.route('user.show', {wrong: 1})).toEqual('http://domain.tld/user/{id}?wrong=1');
    });

    it('Should return undefined for unexistent routes', function(done) {
      expect(Router.route('gusfraba', {id: 1})).toBeUndefined();
    });

    it('Should return root URL', function(done) {
      expect(Router.getRootUrl()).toEqual('http://domain.tld');
    });

    it('Should append query string if there is no parameter in URI', function(done) {
      expect(Router.route('search.index', {q: 'John Doe'})).toEqual('http://domain.tld/search?q=John%20Doe');
    });

    it('Should append query string and replace URI params', function(done) {
      expect(Router.route('search.byType', {type: 'people', q: 'john'})).toEqual('http://domain.tld/search/people?q=john');
    });

    it('Should append many query string params', function(done) {
      expect(Router.route('search.byType', {type: 'people', q: 'john', orderBy: 'asc'})).toEqual('http://domain.tld/search/people?q=john&orderBy=asc');
    });

    it('Should prevent many trailing slashes at the end', function(done) {
      expect(Router.route('home')).toEqual('http://domain.tld');
    });

    it('Should account for optional parameters of routes', function(done) {
      expect(Router.route('cart.add', {article: 'foo', count: 2})).toEqual('http://domain.tld/cart/add/foo/2');
    });

    it('Should ignore unused optional parameters of routes', function(done) {
      expect(Router.route('cart.add', {article: 'foo'})).toEqual('http://domain.tld/cart/add/foo');
    });
  });
});