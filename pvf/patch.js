App.requestOrig = App.request;
App.request = function (t, s, e) {
	t = 'proxy.php/pokevision.com' + t;
	return App.requestOrig(t,s,e);
};