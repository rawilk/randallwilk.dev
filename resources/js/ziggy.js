    var Ziggy = {
        namedRoutes: {"frontend.about":{"uri":"about-me","methods":["GET","HEAD"],"domain":null},"frontend.projects":{"uri":"projects","methods":["GET","HEAD"],"domain":null},"frontend.resume":{"uri":"resume","methods":["GET","HEAD"],"domain":null},"frontend.blog":{"uri":"blog","methods":["GET","HEAD"],"domain":null},"frontend.contact":{"uri":"contact","methods":["GET","HEAD"],"domain":null},"frontend.contact.store":{"uri":"contact","methods":["POST"],"domain":null},"frontend.projects.view":{"uri":"project\/{project}","methods":["GET","HEAD"],"domain":null},"frontend.home":{"uri":"\/","methods":["GET","HEAD"],"domain":null}},
        baseUrl: 'https://randallwilk.dev/',
        baseProtocol: 'https',
        baseDomain: 'randallwilk.dev',
        basePort: false,
        defaultParameters: []
    };

    if (typeof window.Ziggy !== 'undefined') {
        for (var name in window.Ziggy.namedRoutes) {
            Ziggy.namedRoutes[name] = window.Ziggy.namedRoutes[name];
        }
    }

    export {
        Ziggy
    }
