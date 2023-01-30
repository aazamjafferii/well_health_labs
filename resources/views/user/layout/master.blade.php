<!DOCTYPE html>
<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="UTF-8" />
    <script>
        if (
            navigator.userAgent.match(/MSIE|Internet Explorer/i) ||
            navigator.userAgent.match(/Trident\/7\..*?rv:11/i)
        ) {
            var href = document.location.href;
            if (!href.match(/[?&]nowprocket/)) {
                if (href.indexOf("?") == -1) {
                    if (href.indexOf("#") == -1) {
                        document.location.href = href + "?nowprocket=1";
                    } else {
                        document.location.href = href.replace("#", "?nowprocket=1#");
                    }
                } else {
                    if (href.indexOf("#") == -1) {
                        document.location.href = href + "&nowprocket=1";
                    } else {
                        document.location.href = href.replace("#", "&nowprocket=1#");
                    }
                }
            }
        }
    </script>

    <script>
        class RocketLazyLoadScripts {
            constructor() {
                (this.triggerEvents = [
                    "keydown",
                    "mousedown",
                    "mousemove",
                    "touchmove",
                    "touchstart",
                    "touchend",
                    "wheel",
                ]),
                (this.userEventHandler = this._triggerListener.bind(this)),
                (this.touchStartHandler = this._onTouchStart.bind(this)),
                (this.touchMoveHandler = this._onTouchMove.bind(this)),
                (this.touchEndHandler = this._onTouchEnd.bind(this)),
                (this.clickHandler = this._onClick.bind(this)),
                (this.interceptedClicks = []),
                window.addEventListener("pageshow", (e) => {
                        this.persisted = e.persisted;
                    }),
                    window.addEventListener("DOMContentLoaded", () => {
                        this._preconnect3rdParties();
                    }),
                    (this.delayedScripts = {
                        normal: [],
                        async: [],
                        defer: []
                    }),
                    (this.allJQueries = []);
            }
            _addUserInteractionListener(e) {
                document.hidden ?
                    e._triggerListener() :
                    (this.triggerEvents.forEach((t) =>
                            window.addEventListener(t, e.userEventHandler, {
                                passive: !0
                            })
                        ),
                        window.addEventListener("touchstart", e.touchStartHandler, {
                            passive: !0,
                        }),
                        window.addEventListener("mousedown", e.touchStartHandler),
                        document.addEventListener(
                            "visibilitychange",
                            e.userEventHandler
                        ));
            }
            _removeUserInteractionListener() {
                this.triggerEvents.forEach((e) =>
                        window.removeEventListener(e, this.userEventHandler, {
                            passive: !0,
                        })
                    ),
                    document.removeEventListener(
                        "visibilitychange",
                        this.userEventHandler
                    );
            }
            _onTouchStart(e) {
                "HTML" !== e.target.tagName &&
                    (window.addEventListener("touchend", this.touchEndHandler),
                        window.addEventListener("mouseup", this.touchEndHandler),
                        window.addEventListener("touchmove", this.touchMoveHandler, {
                            passive: !0,
                        }),
                        window.addEventListener("mousemove", this.touchMoveHandler),
                        e.target.addEventListener("click", this.clickHandler),
                        this._renameDOMAttribute(e.target, "onclick", "rocket-onclick"));
            }
            _onTouchMove(e) {
                window.removeEventListener("touchend", this.touchEndHandler),
                    window.removeEventListener("mouseup", this.touchEndHandler),
                    window.removeEventListener("touchmove", this.touchMoveHandler, {
                        passive: !0,
                    }),
                    window.removeEventListener("mousemove", this.touchMoveHandler),
                    e.target.removeEventListener("click", this.clickHandler),
                    this._renameDOMAttribute(e.target, "rocket-onclick", "onclick");
            }
            _onTouchEnd(e) {
                window.removeEventListener("touchend", this.touchEndHandler),
                    window.removeEventListener("mouseup", this.touchEndHandler),
                    window.removeEventListener("touchmove", this.touchMoveHandler, {
                        passive: !0,
                    }),
                    window.removeEventListener("mousemove", this.touchMoveHandler);
            }
            _onClick(e) {
                e.target.removeEventListener("click", this.clickHandler),
                    this._renameDOMAttribute(e.target, "rocket-onclick", "onclick"),
                    this.interceptedClicks.push(e),
                    e.preventDefault(),
                    e.stopPropagation(),
                    e.stopImmediatePropagation();
            }
            _replayClicks() {
                window.removeEventListener("touchstart", this.touchStartHandler, {
                        passive: !0,
                    }),
                    window.removeEventListener("mousedown", this.touchStartHandler),
                    this.interceptedClicks.forEach((e) => {
                        e.target.dispatchEvent(
                            new MouseEvent("click", {
                                view: e.view,
                                bubbles: !0,
                                cancelable: !0,
                            })
                        );
                    });
            }
            _renameDOMAttribute(e, t, n) {
                e.hasAttribute &&
                    e.hasAttribute(t) &&
                    (event.target.setAttribute(n, event.target.getAttribute(t)),
                        event.target.removeAttribute(t));
            }
            _triggerListener() {
                this._removeUserInteractionListener(this),
                    "loading" === document.readyState ?
                    document.addEventListener(
                        "DOMContentLoaded",
                        this._loadEverythingNow.bind(this)
                    ) :
                    this._loadEverythingNow();
            }
            _preconnect3rdParties() {
                let e = [];
                document
                    .querySelectorAll("script[type=rocketlazyloadscript]")
                    .forEach((t) => {
                        if (t.hasAttribute("src")) {
                            const n = new URL(t.html).origin;
                            n !== location.origin &&
                                e.push({
                                    src: n,
                                    crossOrigin: t.crossOrigin ||
                                        "module" === t.getAttribute("data-rocket-type"),
                                });
                        }
                    }),
                    (e = [...new Map(e.map((e) => [JSON.stringify(e), e])).values()]),
                    this._batchInjectResourceHints(e, "preconnect");
            }
            async _loadEverythingNow() {
                (this.lastBreath = Date.now()),
                this._delayEventListeners(),
                    this._delayJQueryReady(this),
                    this._handleDocumentWrite(),
                    this._registerAllDelayedScripts(),
                    this._preloadAllScripts(),
                    await this._loadScriptsFromList(this.delayedScripts.normal),
                    await this._loadScriptsFromList(this.delayedScripts.defer),
                    await this._loadScriptsFromList(this.delayedScripts.async);
                try {
                    await this._triggerDOMContentLoaded(),
                        await this._triggerWindowLoad();
                } catch (e) {}
                window.dispatchEvent(new Event("rocket-allScriptsLoaded")),
                    this._replayClicks();
            }
            _registerAllDelayedScripts() {
                document
                    .querySelectorAll("script[type=rocketlazyloadscript]")
                    .forEach((e) => {
                        e.hasAttribute("src") ?
                            e.hasAttribute("async") && !1 !== e.async ?
                            this.delayedScripts.async.push(e) :
                            (e.hasAttribute("defer") && !1 !== e.defer) ||
                            "module" === e.getAttribute("data-rocket-type") ?
                            this.delayedScripts.defer.push(e) :
                            this.delayedScripts.normal.push(e) :
                            this.delayedScripts.normal.push(e);
                    });
            }
            async _transformScript(e) {
                return (
                    await this._littleBreath(),
                    new Promise((t) => {
                        const n = document.createElement("script");
                        [...e.attributes].forEach((e) => {
                                let t = e.nodeName;
                                "type" !== t &&
                                    ("data-rocket-type" === t && (t = "type"),
                                        n.setAttribute(t, e.nodeValue));
                            }),
                            e.hasAttribute("src") ?
                            (n.addEventListener("load", t),
                                n.addEventListener("error", t)) :
                            ((n.text = e.text), t());
                        try {
                            e.parentNode.replaceChild(n, e);
                        } catch (e) {
                            t();
                        }
                    })
                );
            }
            async _loadScriptsFromList(e) {
                const t = e.shift();
                return t ?
                    (await this._transformScript(t), this._loadScriptsFromList(e)) :
                    Promise.resolve();
            }
            _preloadAllScripts() {
                this._batchInjectResourceHints(
                    [
                        ...this.delayedScripts.normal,
                        ...this.delayedScripts.defer,
                        ...this.delayedScripts.async,
                    ],
                    "preload"
                );
            }
            _batchInjectResourceHints(e, t) {
                var n = document.createDocumentFragment();
                e.forEach((e) => {
                        if (e.src) {
                            const i = document.createElement("link");
                            (i.href = e.src),
                            (i.rel = t),
                            "preconnect" !== t && (i.as = "script"),
                                e.getAttribute &&
                                "module" === e.getAttribute("data-rocket-type") &&
                                (i.crossOrigin = !0),
                                e.crossOrigin && (i.crossOrigin = e.crossOrigin),
                                n.appendChild(i);
                        }
                    }),
                    document.head.appendChild(n);
            }
            _delayEventListeners() {
                let e = {};

                function t(t, n) {
                    !(function(t) {
                        function n(n) {
                            return e[t].eventsToRewrite.indexOf(n) >= 0 ? "rocket-" + n : n;
                        }
                        e[t] ||
                            ((e[t] = {
                                    originalFunctions: {
                                        add: t.addEventListener,
                                        remove: t.removeEventListener,
                                    },
                                    eventsToRewrite: [],
                                }),
                                (t.addEventListener = function() {
                                    (arguments[0] = n(arguments[0])),
                                    e[t].originalFunctions.add.apply(t, arguments);
                                }),
                                (t.removeEventListener = function() {
                                    (arguments[0] = n(arguments[0])),
                                    e[t].originalFunctions.remove.apply(t, arguments);
                                }));
                    })(t),
                    e[t].eventsToRewrite.push(n);
                }

                function n(e, t) {
                    let n = e[t];
                    Object.defineProperty(e, t, {
                        get: () => n || function() {},
                        set(i) {
                            e["rocket" + t] = n = i;
                        },
                    });
                }
                t(document, "DOMContentLoaded"),
                    t(window, "DOMContentLoaded"),
                    t(window, "load"),
                    t(window, "pageshow"),
                    t(document, "readystatechange"),
                    n(document, "onreadystatechange"),
                    n(window, "onload"),
                    n(window, "onpageshow");
            }
            _delayJQueryReady(e) {
                let t = window.jQuery;
                Object.defineProperty(window, "jQuery", {
                    get: () => t,
                    set(n) {
                        if (n && n.fn && !e.allJQueries.includes(n)) {
                            n.fn.ready = n.fn.init.prototype.ready = function(t) {
                                e.domReadyFired ?
                                    t.bind(document)(n) :
                                    document.addEventListener("rocket-DOMContentLoaded", () =>
                                        t.bind(document)(n)
                                    );
                            };
                            const t = n.fn.on;
                            (n.fn.on = n.fn.init.prototype.on =
                                function() {
                                    if (this[0] === window) {
                                        function e(e) {
                                            return e
                                                .split(" ")
                                                .map((e) =>
                                                    "load" === e || 0 === e.indexOf("load.") ?
                                                    "rocket-jquery-load" :
                                                    e
                                                )
                                                .join(" ");
                                        }
                                        "string" == typeof arguments[0] ||
                                            arguments[0] instanceof String ?
                                            (arguments[0] = e(arguments[0])) :
                                            "object" == typeof arguments[0] &&
                                            Object.keys(arguments[0]).forEach((t) => {
                                                delete Object.assign(arguments[0], {
                                                    [e(t)]: arguments[0][t],
                                                })[t];
                                            });
                                    }
                                    return t.apply(this, arguments), this;
                                }),
                            e.allJQueries.push(n);
                        }
                        t = n;
                    },
                });
            }
            async _triggerDOMContentLoaded() {
                (this.domReadyFired = !0),
                await this._littleBreath(),
                    document.dispatchEvent(new Event("rocket-DOMContentLoaded")),
                    await this._littleBreath(),
                    window.dispatchEvent(new Event("rocket-DOMContentLoaded")),
                    await this._littleBreath(),
                    document.dispatchEvent(new Event("rocket-readystatechange")),
                    await this._littleBreath(),
                    document.rocketonreadystatechange &&
                    document.rocketonreadystatechange();
            }
            async _triggerWindowLoad() {
                await this._littleBreath(),
                    window.dispatchEvent(new Event("rocket-load")),
                    await this._littleBreath(),
                    window.rocketonload && window.rocketonload(),
                    await this._littleBreath(),
                    this.allJQueries.forEach((e) =>
                        e(window).trigger("rocket-jquery-load")
                    ),
                    await this._littleBreath();
                const e = new Event("rocket-pageshow");
                (e.persisted = this.persisted),
                window.dispatchEvent(e),
                    await this._littleBreath(),
                    window.rocketonpageshow &&
                    window.rocketonpageshow({
                        persisted: this.persisted
                    });
            }
            _handleDocumentWrite() {
                const e = new Map();
                document.write = document.writeln = function(t) {
                    const n = document.currentScript,
                        i = document.createRange(),
                        r = n.parentElement;
                    let o = e.get(n);
                    void 0 === o && ((o = n.nextSibling), e.set(n, o));
                    const s = document.createDocumentFragment();
                    i.setStart(s, 0),
                        s.appendChild(i.createContextualFragment(t)),
                        r.insertBefore(s, o);
                };
            }
            async _littleBreath() {
                Date.now() - this.lastBreath > 45 &&
                    (await this._requestAnimFrame(), (this.lastBreath = Date.now()));
            }
            async _requestAnimFrame() {
                return document.hidden ?
                    new Promise((e) => setTimeout(e)) :
                    new Promise((e) => requestAnimationFrame(e));
            }
            static run() {
                const e = new RocketLazyLoadScripts();
                e._addUserInteractionListener(e);
            }
        }
        RocketLazyLoadScripts.run();
    </script>

    <title>WellHealthLabs</title>

    <link rel="preload" as="style"
        href="https://fonts.googleapis.com/css2?family=Open+Sans+Condensed:wght@700&amp;family=Open+Sans:wght@300;400;600;700&amp;display=swap" />

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Open+Sans+Condensed:wght@700&amp;family=Open+Sans:wght@300;400;600;700&amp;display=swap"
        media="print" onload="this.media='all'" />

    <noscript>
        <link rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Open+Sans+Condensed:wght@700&amp;family=Open+Sans:wght@300;400;600;700&amp;display=swap" />
    </noscript>

    <link rel="stylesheet" href="{{ asset('wp-content/cache/min/1/8806093f05cddc2d31dee7c9412255e3.css') }}"
        media="all" data-minify="1" />

    @include('user.component.metadata')

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="icon" href="{{ asset('images/fabicon.png') }}" style="width: 30px; height: 30px;"/>
    <link rel="pingback" href="xmlrpc.html" />

    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1" />



    <link rel="canonical" href="{{ route('index') }}" />
    <link rel="next" href="{{ route('index') }}" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Well Health Labs" />



    <meta property="og:url" content="{{ route('index') }}" />
    <meta property="og:site_name" content="Well Health labs" />
    <meta property="og:image" content="{{ asset('images/logoshare.png') }}" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="675" />
    <meta property="og:image:type" content="image/png" />
    <meta name="twitter:card" content="summary_large_image" />
    <link href="https://fonts.gstatic.com/" crossorigin rel="preconnect" />

    <style type="text/css">
        img.wp-smiley,
        img.emoji {
            display: inline !important;
            border: none !important;
            box-shadow: none !important;
            height: 1em !important;
            width: 1em !important;
            margin: 0 0.07em !important;
            vertical-align: -0.1em !important;
            background: none !important;
            padding: 0 !important;
        }
    </style>

    <style id="global-styles-inline-css" type="text/css">
        body {
            --wp--preset--color--black: #000000;
            --wp--preset--color--cyan-bluish-gray: #abb8c3;
            --wp--preset--color--white: #ffffff;
            --wp--preset--color--pale-pink: #f78da7;
            --wp--preset--color--vivid-red: #cf2e2e;
            --wp--preset--color--luminous-vivid-orange: #ff6900;
            --wp--preset--color--luminous-vivid-amber: #fcb900;
            --wp--preset--color--light-green-cyan: #7bdcb5;
            --wp--preset--color--vivid-green-cyan: #00d084;
            --wp--preset--color--pale-cyan-blue: #8ed1fc;
            --wp--preset--color--vivid-cyan-blue: #0693e3;
            --wp--preset--color--vivid-purple: #9b51e0;
            --wp--preset--gradient--vivid-cyan-blue-to-vivid-purple: linear-gradient(135deg,
                    rgba(6, 147, 227, 1) 0%,
                    rgb(155, 81, 224) 100%);
            --wp--preset--gradient--light-green-cyan-to-vivid-green-cyan: linear-gradient(135deg,
                    rgb(122, 220, 180) 0%,
                    rgb(0, 208, 130) 100%);
            --wp--preset--gradient--luminous-vivid-amber-to-luminous-vivid-orange: linear-gradient(135deg,
                    rgba(252, 185, 0, 1) 0%,
                    rgba(255, 105, 0, 1) 100%);
            --wp--preset--gradient--luminous-vivid-orange-to-vivid-red: linear-gradient(135deg,
                    rgba(255, 105, 0, 1) 0%,
                    rgb(207, 46, 46) 100%);
            --wp--preset--gradient--very-light-gray-to-cyan-bluish-gray: linear-gradient(135deg,
                    rgb(238, 238, 238) 0%,
                    rgb(169, 184, 195) 100%);
            --wp--preset--gradient--cool-to-warm-spectrum: linear-gradient(135deg,
                    rgb(74, 234, 220) 0%,
                    rgb(151, 120, 209) 20%,
                    rgb(207, 42, 186) 40%,
                    rgb(238, 44, 130) 60%,
                    rgb(251, 105, 98) 80%,
                    rgb(254, 248, 76) 100%);
            --wp--preset--gradient--blush-light-purple: linear-gradient(135deg,
                    rgb(255, 206, 236) 0%,
                    rgb(152, 150, 240) 100%);
            --wp--preset--gradient--blush-bordeaux: linear-gradient(135deg,
                    rgb(254, 205, 165) 0%,
                    rgb(254, 45, 45) 50%,
                    rgb(107, 0, 62) 100%);
            --wp--preset--gradient--luminous-dusk: linear-gradient(135deg,
                    rgb(255, 203, 112) 0%,
                    rgb(199, 81, 192) 50%,
                    rgb(65, 88, 208) 100%);
            --wp--preset--gradient--pale-ocean: linear-gradient(135deg,
                    rgb(255, 245, 203) 0%,
                    rgb(182, 227, 212) 50%,
                    rgb(51, 167, 181) 100%);
            --wp--preset--gradient--electric-grass: linear-gradient(135deg,
                    rgb(202, 248, 128) 0%,
                    rgb(113, 206, 126) 100%);
            --wp--preset--gradient--midnight: linear-gradient(135deg,
                    rgb(2, 3, 129) 0%,
                    rgb(40, 116, 252) 100%);
            --wp--preset--duotone--dark-grayscale: url("#wp-duotone-dark-grayscale");
            --wp--preset--duotone--grayscale: url("#wp-duotone-grayscale");
            --wp--preset--duotone--purple-yellow: url("#wp-duotone-purple-yellow");
            --wp--preset--duotone--blue-red: url("#wp-duotone-blue-red");
            --wp--preset--duotone--midnight: url("#wp-duotone-midnight");
            --wp--preset--duotone--magenta-yellow: url("#wp-duotone-magenta-yellow");
            --wp--preset--duotone--purple-green: url("#wp-duotone-purple-green");
            --wp--preset--duotone--blue-orange: url("#wp-duotone-blue-orange");
            --wp--preset--font-size--small: 13px;
            --wp--preset--font-size--medium: 20px;
            --wp--preset--font-size--large: 36px;
            --wp--preset--font-size--x-large: 42px;
            --wp--preset--spacing--20: 0.44rem;
            --wp--preset--spacing--30: 0.67rem;
            --wp--preset--spacing--40: 1rem;
            --wp--preset--spacing--50: 1.5rem;
            --wp--preset--spacing--60: 2.25rem;
            --wp--preset--spacing--70: 3.38rem;
            --wp--preset--spacing--80: 5.06rem;
        }

        :where(.is-layout-flex) {
            gap: 0.5em;
        }

        body .is-layout-flow>.alignleft {
            float: left;
            margin-inline-start: 0;
            margin-inline-end: 2em;
        }

        body .is-layout-flow>.alignright {
            float: right;
            margin-inline-start: 2em;
            margin-inline-end: 0;
        }

        body .is-layout-flow>.aligncenter {
            margin-left: auto !important;
            margin-right: auto !important;
        }

        body .is-layout-constrained>.alignleft {
            float: left;
            margin-inline-start: 0;
            margin-inline-end: 2em;
        }

        body .is-layout-constrained>.alignright {
            float: right;
            margin-inline-start: 2em;
            margin-inline-end: 0;
        }

        body .is-layout-constrained>.aligncenter {
            margin-left: auto !important;
            margin-right: auto !important;
        }

        body .is-layout-constrained> :where(:not(.alignleft):not(.alignright):not(.alignfull)) {
            max-width: var(--wp--style--global--content-size);
            margin-left: auto !important;
            margin-right: auto !important;
        }

        body .is-layout-constrained>.alignwide {
            max-width: var(--wp--style--global--wide-size);
        }

        body .is-layout-flex {
            display: flex;
        }

        body .is-layout-flex {
            flex-wrap: wrap;
            align-items: center;
        }

        body .is-layout-flex>* {
            margin: 0;
        }

        :where(.wp-block-columns.is-layout-flex) {
            gap: 2em;
        }

        .has-black-color {
            color: var(--wp--preset--color--black) !important;
        }

        .has-cyan-bluish-gray-color {
            color: var(--wp--preset--color--cyan-bluish-gray) !important;
        }

        .has-white-color {
            color: var(--wp--preset--color--white) !important;
        }

        .has-pale-pink-color {
            color: var(--wp--preset--color--pale-pink) !important;
        }

        .has-vivid-red-color {
            color: var(--wp--preset--color--vivid-red) !important;
        }

        .has-luminous-vivid-orange-color {
            color: var(--wp--preset--color--luminous-vivid-orange) !important;
        }

        .has-luminous-vivid-amber-color {
            color: var(--wp--preset--color--luminous-vivid-amber) !important;
        }

        .has-light-green-cyan-color {
            color: var(--wp--preset--color--light-green-cyan) !important;
        }

        .has-vivid-green-cyan-color {
            color: var(--wp--preset--color--vivid-green-cyan) !important;
        }

        .has-pale-cyan-blue-color {
            color: var(--wp--preset--color--pale-cyan-blue) !important;
        }

        .has-vivid-cyan-blue-color {
            color: var(--wp--preset--color--vivid-cyan-blue) !important;
        }

        .has-vivid-purple-color {
            color: var(--wp--preset--color--vivid-purple) !important;
        }

        .has-black-background-color {
            background-color: var(--wp--preset--color--black) !important;
        }

        .has-cyan-bluish-gray-background-color {
            background-color: var(--wp--preset--color--cyan-bluish-gray) !important;
        }

        .has-white-background-color {
            background-color: var(--wp--preset--color--white) !important;
        }

        .has-pale-pink-background-color {
            background-color: var(--wp--preset--color--pale-pink) !important;
        }

        .has-vivid-red-background-color {
            background-color: var(--wp--preset--color--vivid-red) !important;
        }

        .has-luminous-vivid-orange-background-color {
            background-color: var(--wp--preset--color--luminous-vivid-orange) !important;
        }

        .has-luminous-vivid-amber-background-color {
            background-color: var(--wp--preset--color--luminous-vivid-amber) !important;
        }

        .has-light-green-cyan-background-color {
            background-color: var(--wp--preset--color--light-green-cyan) !important;
        }

        .has-vivid-green-cyan-background-color {
            background-color: var(--wp--preset--color--vivid-green-cyan) !important;
        }

        .has-pale-cyan-blue-background-color {
            background-color: var(--wp--preset--color--pale-cyan-blue) !important;
        }

        .has-vivid-cyan-blue-background-color {
            background-color: var(--wp--preset--color--vivid-cyan-blue) !important;
        }

        .has-vivid-purple-background-color {
            background-color: var(--wp--preset--color--vivid-purple) !important;
        }

        .has-black-border-color {
            border-color: var(--wp--preset--color--black) !important;
        }

        .has-cyan-bluish-gray-border-color {
            border-color: var(--wp--preset--color--cyan-bluish-gray) !important;
        }

        .has-white-border-color {
            border-color: var(--wp--preset--color--white) !important;
        }

        .has-pale-pink-border-color {
            border-color: var(--wp--preset--color--pale-pink) !important;
        }

        .has-vivid-red-border-color {
            border-color: var(--wp--preset--color--vivid-red) !important;
        }

        .has-luminous-vivid-orange-border-color {
            border-color: var(--wp--preset--color--luminous-vivid-orange) !important;
        }

        .has-luminous-vivid-amber-border-color {
            border-color: var(--wp--preset--color--luminous-vivid-amber) !important;
        }

        .has-light-green-cyan-border-color {
            border-color: var(--wp--preset--color--light-green-cyan) !important;
        }

        .has-vivid-green-cyan-border-color {
            border-color: var(--wp--preset--color--vivid-green-cyan) !important;
        }

        .has-pale-cyan-blue-border-color {
            border-color: var(--wp--preset--color--pale-cyan-blue) !important;
        }

        .has-vivid-cyan-blue-border-color {
            border-color: var(--wp--preset--color--vivid-cyan-blue) !important;
        }

        .has-vivid-purple-border-color {
            border-color: var(--wp--preset--color--vivid-purple) !important;
        }

        .has-vivid-cyan-blue-to-vivid-purple-gradient-background {
            background: var(--wp--preset--gradient--vivid-cyan-blue-to-vivid-purple) !important;
        }

        .has-light-green-cyan-to-vivid-green-cyan-gradient-background {
            background: var(--wp--preset--gradient--light-green-cyan-to-vivid-green-cyan) !important;
        }

        .has-luminous-vivid-amber-to-luminous-vivid-orange-gradient-background {
            background: var(--wp--preset--gradient--luminous-vivid-amber-to-luminous-vivid-orange) !important;
        }

        .has-luminous-vivid-orange-to-vivid-red-gradient-background {
            background: var(--wp--preset--gradient--luminous-vivid-orange-to-vivid-red) !important;
        }

        .has-very-light-gray-to-cyan-bluish-gray-gradient-background {
            background: var(--wp--preset--gradient--very-light-gray-to-cyan-bluish-gray) !important;
        }

        .has-cool-to-warm-spectrum-gradient-background {
            background: var(--wp--preset--gradient--cool-to-warm-spectrum) !important;
        }

        .has-blush-light-purple-gradient-background {
            background: var(--wp--preset--gradient--blush-light-purple) !important;
        }

        .has-blush-bordeaux-gradient-background {
            background: var(--wp--preset--gradient--blush-bordeaux) !important;
        }

        .has-luminous-dusk-gradient-background {
            background: var(--wp--preset--gradient--luminous-dusk) !important;
        }

        .has-pale-ocean-gradient-background {
            background: var(--wp--preset--gradient--pale-ocean) !important;
        }

        .has-electric-grass-gradient-background {
            background: var(--wp--preset--gradient--electric-grass) !important;
        }

        .has-midnight-gradient-background {
            background: var(--wp--preset--gradient--midnight) !important;
        }

        .has-small-font-size {
            font-size: var(--wp--preset--font-size--small) !important;
        }

        .has-medium-font-size {
            font-size: var(--wp--preset--font-size--medium) !important;
        }

        .has-large-font-size {
            font-size: var(--wp--preset--font-size--large) !important;
        }

        .has-x-large-font-size {
            font-size: var(--wp--preset--font-size--x-large) !important;
        }

        .wp-block-navigation a:where(:not(.wp-element-button)) {
            color: inherit;
        }

        :where(.wp-block-columns.is-layout-flex) {
            gap: 2em;
        }

        .wp-block-pullquote {
            font-size: 1.5em;
            line-height: 1.6;
        }
    </style>

    <style id="moove_gdpr_frontend-inline-css" type="text/css">
        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-tab-main h3.tab-title,
        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-tab-main span.tab-title,
        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-left-content #moove-gdpr-menu li a,
        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-left-content #moove-gdpr-menu li button,
        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-left-content .moove-gdpr-branding-cnt a,
        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-footer-content .moove-gdpr-button-holder a.mgbutton,
        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-footer-content .moove-gdpr-button-holder button.mgbutton,
        #moove_gdpr_cookie_modal .cookie-switch .cookie-slider:after,
        #moove_gdpr_cookie_modal .cookie-switch .slider:after,
        #moove_gdpr_cookie_modal .switch .cookie-slider:after,
        #moove_gdpr_cookie_modal .switch .slider:after,
        #moove_gdpr_cookie_info_bar .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content p,
        #moove_gdpr_cookie_info_bar .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content p a,
        #moove_gdpr_cookie_info_bar .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content a.mgbutton,
        #moove_gdpr_cookie_info_bar .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content button.mgbutton,
        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-tab-main .moove-gdpr-tab-main-content h1,
        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-tab-main .moove-gdpr-tab-main-content h2,
        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-tab-main .moove-gdpr-tab-main-content h3,
        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-tab-main .moove-gdpr-tab-main-content h4,
        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-tab-main .moove-gdpr-tab-main-content h5,
        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-tab-main .moove-gdpr-tab-main-content h6,
        #moove_gdpr_cookie_modal .moove-gdpr-modal-content.moove_gdpr_modal_theme_v2 .moove-gdpr-modal-title .tab-title,
        #moove_gdpr_cookie_modal .moove-gdpr-modal-content.moove_gdpr_modal_theme_v2 .moove-gdpr-tab-main h3.tab-title,
        #moove_gdpr_cookie_modal .moove-gdpr-modal-content.moove_gdpr_modal_theme_v2 .moove-gdpr-tab-main span.tab-title,
        #moove_gdpr_cookie_modal .moove-gdpr-modal-content.moove_gdpr_modal_theme_v2 .moove-gdpr-branding-cnt a {
            font-weight: inherit;
        }

        #moove_gdpr_cookie_modal,
        #moove_gdpr_cookie_info_bar,
        .gdpr_cookie_settings_shortcode_content {
            font-family: inherit;
        }

        #moove_gdpr_save_popup_settings_button {
            background-color: #373737;
            color: #fff;
        }

        #moove_gdpr_save_popup_settings_button:hover {
            background-color: #000;
        }

        #moove_gdpr_cookie_info_bar .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content a.mgbutton,
        #moove_gdpr_cookie_info_bar .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content button.mgbutton {
            background-color: #08677f;
        }

        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-footer-content .moove-gdpr-button-holder a.mgbutton,
        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-footer-content .moove-gdpr-button-holder button.mgbutton,
        .gdpr_cookie_settings_shortcode_content .gdpr-shr-button.button-green {
            background-color: #08677f;
            border-color: #08677f;
        }

        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-footer-content .moove-gdpr-button-holder a.mgbutton:hover,
        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-footer-content .moove-gdpr-button-holder button.mgbutton:hover,
        .gdpr_cookie_settings_shortcode_content .gdpr-shr-button.button-green:hover {
            background-color: #fff;
            color: #08677f;
        }

        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-close i,
        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-close span.gdpr-icon {
            background-color: #08677f;
            border: 1px solid #08677f;
        }

        #moove_gdpr_cookie_info_bar span.change-settings-button.focus-g,
        #moove_gdpr_cookie_info_bar span.change-settings-button:focus {
            -webkit-box-shadow: 0 0 1px 3px #08677f;
            -moz-box-shadow: 0 0 1px 3px #08677f;
            box-shadow: 0 0 1px 3px #08677f;
        }

        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-close i:hover,
        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-close span.gdpr-icon:hover,
        #moove_gdpr_cookie_info_bar span[data-href]>u.change-settings-button {
            color: #08677f;
        }

        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-left-content #moove-gdpr-menu li.menu-item-selected a span.gdpr-icon,
        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-left-content #moove-gdpr-menu li.menu-item-selected button span.gdpr-icon {
            color: inherit;
        }

        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-left-content #moove-gdpr-menu li a span.gdpr-icon,
        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-left-content #moove-gdpr-menu li button span.gdpr-icon {
            color: inherit;
        }

        #moove_gdpr_cookie_modal .gdpr-acc-link {
            line-height: 0;
            font-size: 0;
            color: transparent;
            position: absolute;
        }

        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-close:hover i,
        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-left-content #moove-gdpr-menu li a,
        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-left-content #moove-gdpr-menu li button,
        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-left-content #moove-gdpr-menu li button i,
        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-modal-left-content #moove-gdpr-menu li a i,
        #moove_gdpr_cookie_modal .moove-gdpr-modal-content .moove-gdpr-tab-main .moove-gdpr-tab-main-content a:hover,
        #moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content a.mgbutton:hover,
        #moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content button.mgbutton:hover,
        #moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content a:hover,
        #moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content button:hover,
        #moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content span.change-settings-button:hover,
        #moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content u.change-settings-button:hover,
        #moove_gdpr_cookie_info_bar span[data-href]>u.change-settings-button,
        #moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content a.mgbutton.focus-g,
        #moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content button.mgbutton.focus-g,
        #moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content a.focus-g,
        #moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content button.focus-g,
        #moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content a.mgbutton:focus,
        #moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content button.mgbutton:focus,
        #moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content a:focus,
        #moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content button:focus,
        #moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content span.change-settings-button.focus-g,
        span.change-settings-button:focus,
        #moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content u.change-settings-button.focus-g,
        #moove_gdpr_cookie_info_bar.moove-gdpr-dark-scheme .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content u.change-settings-button:focus {
            color: #08677f;
        }

        #moove_gdpr_cookie_modal.gdpr_lightbox-hide {
            display: none;
        }

        #moove_gdpr_cookie_info_bar.gdpr-full-screen-infobar .moove-gdpr-info-bar-container .moove-gdpr-info-bar-content .moove-gdpr-cookie-notice p a {
            color: #08677f !important;
        }
    </style>

    <script
      type="rocketlazyloadscript"
      data-rocket-type="text/javascript"
      src="{{ asset('wp-includes/js/jquery/jquery.mina7a0.js?ver=3.6.1') }}"
      id="jquery-core-js"
      defer>
    </script>

    <script
      type="rocketlazyloadscript"
      data-rocket-type="text/javascript"
      id="jquery-core-js-after">

      window.addEventListener('DOMContentLoaded', function() {
          jQuery(document).ready( function($) {
          $( '#wp-admin-bar-my-sites-search.hide-if-no-js' ).show();
          $( '#wp-admin-bar-my-sites-search input' ).on( 'input', function( ) {
              var searchValRegex = new RegExp( $(this).val(), 'i');
              $( '#wp-admin-bar-my-sites-list > li.menupop' ).hide().filter( function() {
              return searchValRegex.test( $(this).find( '> a' ).text() );
              }).show();
              });
          });
      });
    </script>

    <script
      type="rocketlazyloadscript"
      data-rocket-type="text/javascript"
      src="{{ asset('wp-includes/js/jquery/jquery-migrate.mind617.js?ver=3.3.2') }}"
      id="jquery-migrate-js"
      defer>
    </script>

    <script
      type="rocketlazyloadscript"
      data-minify="1"
      data-rocket-type="text/javascript"
      src="{{ asset('wp-content/cache/min/1/wp-content/themes/altn2018/js/customebad.js?ver=1668177864') }}"
      id="custom-js"
      defer>
    </script>

    <script
      type="rocketlazyloadscript"
      data-minify="1"
      data-rocket-type="text/javascript"
      src="{{ asset('wp-content/cache/min/1/wp-content/themes/altn2018/js/locationebad.js?ver=1668177864') }}"
      id="location-js"
      defer>
    </script>

    <script
      type="rocketlazyloadscript"
      data-rocket-type="text/javascript"
      src="{{ asset('wp-content/themes/altn2018/js/jquery-scrollpanel-0.7.0.min276f.js?ver=0.7.0') }}"
      id="scrollpanel-js"
      defer>
    </script>

    <style>
        button#responsive-menu-button,
        #responsive-menu-container {
            display: none;
            -webkit-text-size-adjust: 100%;
        }

        @media screen and (max-width: 767px) {
            #responsive-menu-container {
                display: block;
                position: fixed;
                top: 0;
                bottom: 0;
                z-index: 99998;
                padding-bottom: 5px;
                margin-bottom: -5px;
                outline: 1px solid transparent;
                overflow-y: auto;
                overflow-x: hidden;
            }

            #responsive-menu-container .responsive-menu-search-box {
                width: 100%;
                padding: 0 2%;
                border-radius: 2px;
                height: 50px;
                -webkit-appearance: none;
            }

            #responsive-menu-container.push-left,
            #responsive-menu-container.slide-left {
                transform: translateX(-100%);
                -ms-transform: translateX(-100%);
                -webkit-transform: translateX(-100%);
                -moz-transform: translateX(-100%);
            }

            .responsive-menu-open #responsive-menu-container.push-left,
            .responsive-menu-open #responsive-menu-container.slide-left {
                transform: translateX(0);
                -ms-transform: translateX(0);
                -webkit-transform: translateX(0);
                -moz-transform: translateX(0);
            }

            #responsive-menu-container.push-top,
            #responsive-menu-container.slide-top {
                transform: translateY(-100%);
                -ms-transform: translateY(-100%);
                -webkit-transform: translateY(-100%);
                -moz-transform: translateY(-100%);
            }

            .responsive-menu-open #responsive-menu-container.push-top,
            .responsive-menu-open #responsive-menu-container.slide-top {
                transform: translateY(0);
                -ms-transform: translateY(0);
                -webkit-transform: translateY(0);
                -moz-transform: translateY(0);
            }

            #responsive-menu-container.push-right,
            #responsive-menu-container.slide-right {
                transform: translateX(100%);
                -ms-transform: translateX(100%);
                -webkit-transform: translateX(100%);
                -moz-transform: translateX(100%);
            }

            .responsive-menu-open #responsive-menu-container.push-right,
            .responsive-menu-open #responsive-menu-container.slide-right {
                transform: translateX(0);
                -ms-transform: translateX(0);
                -webkit-transform: translateX(0);
                -moz-transform: translateX(0);
            }

            #responsive-menu-container.push-bottom,
            #responsive-menu-container.slide-bottom {
                transform: translateY(100%);
                -ms-transform: translateY(100%);
                -webkit-transform: translateY(100%);
                -moz-transform: translateY(100%);
            }

            .responsive-menu-open #responsive-menu-container.push-bottom,
            .responsive-menu-open #responsive-menu-container.slide-bottom {
                transform: translateY(0);
                -ms-transform: translateY(0);
                -webkit-transform: translateY(0);
                -moz-transform: translateY(0);
            }

            #responsive-menu-container,
            #responsive-menu-container:before,
            #responsive-menu-container:after,
            #responsive-menu-container *,
            #responsive-menu-container *:before,
            #responsive-menu-container *:after {
                box-sizing: border-box;
                margin: 0;
                padding: 0;
            }

            #responsive-menu-container #responsive-menu-search-box,
            #responsive-menu-container #responsive-menu-additional-content,
            #responsive-menu-container #responsive-menu-title {
                padding: 25px 5%;
            }

            #responsive-menu-container #responsive-menu,
            #responsive-menu-container #responsive-menu ul {
                width: 100%;
            }

            #responsive-menu-container #responsive-menu ul.responsive-menu-submenu {
                display: none;
            }

            #responsive-menu-container #responsive-menu ul.responsive-menu-submenu.responsive-menu-submenu-open {
                display: block;
            }

            #responsive-menu-container #responsive-menu ul.responsive-menu-submenu-depth-1 a.responsive-menu-item-link {
                padding-left: 10%;
            }

            #responsive-menu-container #responsive-menu ul.responsive-menu-submenu-depth-2 a.responsive-menu-item-link {
                padding-left: 15%;
            }

            #responsive-menu-container #responsive-menu ul.responsive-menu-submenu-depth-3 a.responsive-menu-item-link {
                padding-left: 20%;
            }

            #responsive-menu-container #responsive-menu ul.responsive-menu-submenu-depth-4 a.responsive-menu-item-link {
                padding-left: 25%;
            }

            #responsive-menu-container #responsive-menu ul.responsive-menu-submenu-depth-5 a.responsive-menu-item-link {
                padding-left: 30%;
            }

            #responsive-menu-container li.responsive-menu-item {
                width: 100%;
                list-style: none;
            }

            #responsive-menu-container li.responsive-menu-item a {
                width: 100%;
                display: block;
                text-decoration: none;
                position: relative;
            }

            #responsive-menu-container #responsive-menu li.responsive-menu-item a {
                padding: 0 5%;
            }

            #responsive-menu-container .responsive-menu-submenu li.responsive-menu-item a {
                padding: 0 5%;
            }

            #responsive-menu-container li.responsive-menu-item a .fa {
                margin-right: 15px;
            }

            #responsive-menu-container li.responsive-menu-item a .responsive-menu-subarrow {
                position: absolute;
                top: 0;
                bottom: 0;
                text-align: center;
                overflow: hidden;
            }

            #responsive-menu-container li.responsive-menu-item a .responsive-menu-subarrow .fa {
                margin-right: 0;
            }

            button#responsive-menu-button .responsive-menu-button-icon-inactive {
                display: none;
            }

            button#responsive-menu-button {
                z-index: 99999;
                display: none;
                overflow: hidden;
                outline: none;
            }

            button#responsive-menu-button img {
                max-width: 100%;
            }

            .responsive-menu-label {
                display: inline-block;
                font-weight: 600;
                margin: 0 5px;
                vertical-align: middle;
            }

            .responsive-menu-label .responsive-menu-button-text-open {
                display: none;
            }

            .responsive-menu-accessible {
                display: inline-block;
            }

            .responsive-menu-accessible .responsive-menu-box {
                display: inline-block;
                vertical-align: middle;
            }

            .responsive-menu-label.responsive-menu-label-top,
            .responsive-menu-label.responsive-menu-label-bottom {
                display: block;
                margin: 0 auto;
            }

            button#responsive-menu-button {
                padding: 0 0;
                display: inline-block;
                cursor: pointer;
                transition-property: opacity, filter;
                transition-duration: 0.15s;
                transition-timing-function: linear;
                font: inherit;
                color: inherit;
                text-transform: none;
                background-color: transparent;
                border: 0;
                margin: 0;
                overflow: visible;
            }

            .responsive-menu-box {
                width: 35px;
                height: 72px;
                display: inline-block;
                position: relative;
            }

            .responsive-menu-inner {
                display: block;
                top: 50%;
                margin-top: -1.5px;
            }

            .responsive-menu-inner,
            .responsive-menu-inner::before,
            .responsive-menu-inner::after {
                width: 35px;
                height: 3px;
                background-color: #ffffff;
                border-radius: 4px;
                position: absolute;
                transition-property: transform;
                transition-duration: 0.15s;
                transition-timing-function: ease;
            }

            .responsive-menu-open .responsive-menu-inner,
            .responsive-menu-open .responsive-menu-inner::before,
            .responsive-menu-open .responsive-menu-inner::after {
                background-color: #ffffff;
            }

            button#responsive-menu-button:hover .responsive-menu-inner,
            button#responsive-menu-button:hover .responsive-menu-inner::before,
            button#responsive-menu-button:hover .responsive-menu-inner::after,
            button#responsive-menu-button:hover .responsive-menu-open .responsive-menu-inner,
            button#responsive-menu-button:hover .responsive-menu-open .responsive-menu-inner::before,
            button#responsive-menu-button:hover .responsive-menu-open .responsive-menu-inner::after,
            button#responsive-menu-button:focus .responsive-menu-inner,
            button#responsive-menu-button:focus .responsive-menu-inner::before,
            button#responsive-menu-button:focus .responsive-menu-inner::after,
            button#responsive-menu-button:focus .responsive-menu-open .responsive-menu-inner,
            button#responsive-menu-button:focus .responsive-menu-open .responsive-menu-inner::before,
            button#responsive-menu-button:focus .responsive-menu-open .responsive-menu-inner::after {
                background-color: #ffffff;
            }

            .responsive-menu-inner::before,
            .responsive-menu-inner::after {
                content: "";
                display: block;
            }

            .responsive-menu-inner::before {
                top: -8px;
            }

            .responsive-menu-inner::after {
                bottom: -8px;
            }

            .responsive-menu-boring .responsive-menu-inner,
            .responsive-menu-boring .responsive-menu-inner::before,
            .responsive-menu-boring .responsive-menu-inner::after {
                transition-property: none;
            }

            .responsive-menu-boring.is-active .responsive-menu-inner {
                transform: rotate(45deg);
            }

            .responsive-menu-boring.is-active .responsive-menu-inner::before {
                top: 0;
                opacity: 0;
            }

            .responsive-menu-boring.is-active .responsive-menu-inner::after {
                bottom: 0;
                transform: rotate(-90deg);
            }

            button#responsive-menu-button {
                width: 55px;
                height: 55px;
                position: absolute;
                top: 15px;
                left: 5%;
            }

            button#responsive-menu-button .responsive-menu-box {
                color: #ffffff;
            }

            .responsive-menu-open button#responsive-menu-button .responsive-menu-box {
                color: #ffffff;
            }

            .responsive-menu-label {
                color: #ffffff;
                font-size: 11px;
                line-height: 13px;
                font-family: ""Open Sans "";
            }

            button#responsive-menu-button {
                display: inline-block;
                transition: transform 0.5s, background-color 0.5s;
            }

            #responsive-menu-container {
                width: 85%;
                left: 0;
                transition: transform 0.5s;
                text-align: left;
                background: #031d24;
            }

            #responsive-menu-container #responsive-menu-wrapper {
                background: #031d24;
            }

            #responsive-menu-container #responsive-menu-additional-content {
                color: #ffffff;
            }

            #responsive-menu-container .responsive-menu-search-box {
                background: #ffffff;
                border: 2px solid #dadada;
                color: #333333;
            }

            #responsive-menu-container .responsive-menu-search-box:-ms-input-placeholder {
                color: #c7c7cd;
            }

            #responsive-menu-container .responsive-menu-search-box::-webkit-input-placeholder {
                color: #c7c7cd;
            }

            #responsive-menu-container .responsive-menu-search-box:-moz-placeholder {
                color: #c7c7cd;
                opacity: 1;
            }

            #responsive-menu-container .responsive-menu-search-box::-moz-placeholder {
                color: #c7c7cd;
                opacity: 1;
            }

            #responsive-menu-container .responsive-menu-item-link,
            #responsive-menu-container #responsive-menu-title,
            #responsive-menu-container .responsive-menu-subarrow {
                transition: background-color 0.5s, border-color 0.5s, color 0.5s;
            }

            #responsive-menu-container #responsive-menu-title {
                background-color: #031d24;
                color: #ffffff;
                font-size: 30px;
                text-align: left;
            }

            #responsive-menu-container #responsive-menu-title a {
                color: #ffffff;
                font-size: 30px;
                text-decoration: none;
            }

            #responsive-menu-container #responsive-menu-title a:hover {
                color: #ffffff;
            }

            #responsive-menu-container #responsive-menu-title:hover {
                background-color: #024051;
                color: #ffffff;
            }

            #responsive-menu-container #responsive-menu-title:hover a {
                color: #ffffff;
            }

            #responsive-menu-container #responsive-menu-title #responsive-menu-title-image {
                display: inline-block;
                vertical-align: middle;
                max-width: 100%;
                margin-bottom: 15px;
            }

            #responsive-menu-container #responsive-menu-title #responsive-menu-title-image img {
                max-width: 100%;
            }

            #responsive-menu-container #responsive-menu>li.responsive-menu-item:first-child>a {
                border-top: 1px solid #9aa5a7;
            }

            #responsive-menu-container #responsive-menu li.responsive-menu-item .responsive-menu-item-link {
                font-size: 16px;
            }

            #responsive-menu-container #responsive-menu li.responsive-menu-item a {
                line-height: 40px;
                border-bottom: 1px solid #9aa5a7;
                color: #ffffff;
                background-color: #031d24;
                height: 40px;
            }

            #responsive-menu-container #responsive-menu li.responsive-menu-item a:hover {
                color: #ffffff;
                background-color: #024051;
                border-color: #ffffff;
            }

            #responsive-menu-container #responsive-menu li.responsive-menu-item a:hover .responsive-menu-subarrow {
                color: #024051;
                border-color: #024051;
                background-color: #031d24;
            }

            #responsive-menu-container #responsive-menu li.responsive-menu-item a:hover .responsive-menu-subarrow.responsive-menu-subarrow-active {
                color: #024051;
                border-color: #024051;
                background-color: #031d24;
            }

            #responsive-menu-container #responsive-menu li.responsive-menu-item a .responsive-menu-subarrow {
                right: 0;
                height: 39px;
                line-height: 39px;
                width: 40px;
                color: #031d24;
                border-left: 1px solid #031d24;
                background-color: #031d24;
            }

            #responsive-menu-container #responsive-menu li.responsive-menu-item a .responsive-menu-subarrow.responsive-menu-subarrow-active {
                color: #024051;
                border-color: #024051;
                background-color: #031d24;
            }

            #responsive-menu-container #responsive-menu li.responsive-menu-item a .responsive-menu-subarrow.responsive-menu-subarrow-active:hover {
                color: #024051;
                border-color: #024051;
                background-color: #031d24;
            }

            #responsive-menu-container #responsive-menu li.responsive-menu-item a .responsive-menu-subarrow:hover {
                color: #024051;
                border-color: #024051;
                background-color: #031d24;
            }

            #responsive-menu-container #responsive-menu li.responsive-menu-current-item>.responsive-menu-item-link {
                background-color: #024051;
                color: #ffffff;
                border-color: #ffffff;
            }

            #responsive-menu-container #responsive-menu li.responsive-menu-current-item>.responsive-menu-item-link:hover {
                background-color: #024051;
                color: #ffffff;
                border-color: #ffffff;
            }

            #responsive-menu-container #responsive-menu ul.responsive-menu-submenu li.responsive-menu-item .responsive-menu-item-link {
                font-size: 16px;
                text-align: left;
            }

            #responsive-menu-container #responsive-menu ul.responsive-menu-submenu li.responsive-menu-item a {
                height: 40px;
                line-height: 0px;
                border-bottom: 1px solid #9aa5a7;
                color: #ffffff;
                background-color: #031d24;
            }

            #responsive-menu-container #responsive-menu ul.responsive-menu-submenu li.responsive-menu-item a:hover {
                color: #ffffff;
                background-color: #024051;
                border-color: #ffffff;
            }

            #responsive-menu-container #responsive-menu ul.responsive-menu-submenu li.responsive-menu-item a:hover .responsive-menu-subarrow {
                color: #024051;
                border-color: #024051;
                background-color: #024051;
            }

            #responsive-menu-container #responsive-menu ul.responsive-menu-submenu li.responsive-menu-item a:hover .responsive-menu-subarrow.responsive-menu-subarrow-active {
                color: #024051;
                border-color: #024051;
                background-color: #024051;
            }

            #responsive-menu-container #responsive-menu ul.responsive-menu-submenu li.responsive-menu-item a .responsive-menu-subarrow {
                left: unset;
                right: 0;
                height: 39px;
                line-height: 39px;
                width: 40px;
                color: #031d24;
                border-left: 1px solid #031d24 !important;
                border-right: unset !important;
                background-color: #031d24;
            }

            #responsive-menu-container #responsive-menu ul.responsive-menu-submenu li.responsive-menu-item a .responsive-menu-subarrow.responsive-menu-subarrow-active {
                color: #024051;
                border-color: #024051;
                background-color: #024051;
            }

            #responsive-menu-container #responsive-menu ul.responsive-menu-submenu li.responsive-menu-item a .responsive-menu-subarrow.responsive-menu-subarrow-active:hover {
                color: #024051;
                border-color: #024051;
                background-color: #024051;
            }

            #responsive-menu-container #responsive-menu ul.responsive-menu-submenu li.responsive-menu-item a .responsive-menu-subarrow:hover {
                color: #024051;
                border-color: #024051;
                background-color: #024051;
            }

            #responsive-menu-container #responsive-menu ul.responsive-menu-submenu li.responsive-menu-current-item>.responsive-menu-item-link {
                background-color: #024051;
                color: #ffffff;
                border-color: #ffffff;
            }

            #responsive-menu-container #responsive-menu ul.responsive-menu-submenu li.responsive-menu-current-item>.responsive-menu-item-link:hover {
                background-color: #024051;
                color: #ffffff;
                border-color: #ffffff;
            }

            navmain {
                display: none !important;
            }
        }
    </style>

    <script type="rocketlazyloadscript">
          window.addEventListener('DOMContentLoaded', function() {
            jQuery(document).ready(function($) {

          var ResponsiveMenu = {
              trigger: '#responsive-menu-button',
              animationSpeed:500,
              breakpoint:767,
              pushButton: 'off',
              animationType: 'slide',
              animationSide: 'left',
              pageWrapper: '',
              isOpen: false,
              triggerTypes: 'click',
              activeClass: 'is-active',
              container: '#responsive-menu-container',
              openClass: 'responsive-menu-open',
              accordion: 'off',
              activeArrow: '▲',
              inactiveArrow: '▼',
              wrapper: '#responsive-menu-wrapper',
              closeOnBodyClick: 'off',
              closeOnLinkClick: 'off',
              itemTriggerSubMenu: 'off',
              linkElement: '.responsive-menu-item-link',
              subMenuTransitionTime:200,
              openMenu: function() {
                  $(this.trigger).addClass(this.activeClass);
                  $('html').addClass(this.openClass);
                  $('.responsive-menu-button-icon-active').hide();
                  $('.responsive-menu-button-icon-inactive').show();
                  this.setButtonTextOpen();
                  this.setWrapperTranslate();
                  this.isOpen = true;
              },
              closeMenu: function() {
                  $(this.trigger).removeClass(this.activeClass);
                  $('html').removeClass(this.openClass);
                  $('.responsive-menu-button-icon-inactive').hide();
                  $('.responsive-menu-button-icon-active').show();
                  this.setButtonText();
                  this.clearWrapperTranslate();
                  this.isOpen = false;
              },
              setButtonText: function() {
                  if($('.responsive-menu-button-text-open').length > 0 && $('.responsive-menu-button-text').length > 0) {
                      $('.responsive-menu-button-text-open').hide();
                      $('.responsive-menu-button-text').show();
                  }
              },
              setButtonTextOpen: function() {
                  if($('.responsive-menu-button-text').length > 0 && $('.responsive-menu-button-text-open').length > 0) {
                      $('.responsive-menu-button-text').hide();
                      $('.responsive-menu-button-text-open').show();
                  }
              },
              triggerMenu: function() {
                  this.isOpen ? this.closeMenu() : this.openMenu();
              },
              triggerSubArrow: function(subarrow) {
                  var sub_menu = $(subarrow).parent().siblings('.responsive-menu-submenu');
                  var self = this;
                  if(this.accordion == 'on') {

                      /* Get Top Most Parent and the siblings */

                      var top_siblings = sub_menu.parents('.responsive-menu-item-has-children').last().siblings('.responsive-menu-item-has-children');
                      var first_siblings = sub_menu.parents('.responsive-menu-item-has-children').first().siblings('.responsive-menu-item-has-children');

                      /* Close up just the top level parents to key the rest as it was */

                      top_siblings.children('.responsive-menu-submenu').slideUp(self.subMenuTransitionTime, 'linear').removeClass('responsive-menu-submenu-open');

                      /* Set each parent arrow to inactive */

                      top_siblings.each(function() {
                          $(this).find('.responsive-menu-subarrow').first().html(self.inactiveArrow);
                          $(this).find('.responsive-menu-subarrow').first().removeClass('responsive-menu-subarrow-active');
                      });

                      /* Now Repeat for the current item siblings */

                      first_siblings.children('.responsive-menu-submenu').slideUp(self.subMenuTransitionTime, 'linear').removeClass('responsive-menu-submenu-open');
                      first_siblings.each(function() {
                          $(this).find('.responsive-menu-subarrow').first().html(self.inactiveArrow);
                          $(this).find('.responsive-menu-subarrow').first().removeClass('responsive-menu-subarrow-active');
                      });
                  }
                  if(sub_menu.hasClass('responsive-menu-submenu-open')) {
                      sub_menu.slideUp(self.subMenuTransitionTime, 'linear').removeClass('responsive-menu-submenu-open');
                      $(subarrow).html(this.inactiveArrow);
                      $(subarrow).removeClass('responsive-menu-subarrow-active');
                  } else {
                      sub_menu.slideDown(self.subMenuTransitionTime, 'linear').addClass('responsive-menu-submenu-open');
                      $(subarrow).html(this.activeArrow);
                      $(subarrow).addClass('responsive-menu-subarrow-active');
                  }
              },
              menuHeight: function() {
                  return $(this.container).height();
              },
              menuWidth: function() {
                  return $(this.container).width();
              },
              wrapperHeight: function() {
                  return $(this.wrapper).height();
              },
              setWrapperTranslate: function() {
                  switch(this.animationSide) {
                      case 'left':
                          translate = 'translateX(' + this.menuWidth() + 'px)'; break;
                      case 'right':
                          translate = 'translateX(-' + this.menuWidth() + 'px)'; break;
                      case 'top':
                          translate = 'translateY(' + this.wrapperHeight() + 'px)'; break;
                      case 'bottom':
                          translate = 'translateY(-' + this.menuHeight() + 'px)'; break;
                  }
                  if(this.animationType == 'push') {
                      $(this.pageWrapper).css({'transform':translate});
                      $('html, body').css('overflow-x', 'hidden');
                  }
                  if(this.pushButton == 'on') {
                      $('#responsive-menu-button').css({'transform':translate});
                  }
              },
              clearWrapperTranslate: function() {
                  var self = this;
                  if(this.animationType == 'push') {
                      $(this.pageWrapper).css({'transform':''});
                      setTimeout(function() {
                          $('html, body').css('overflow-x', '');
                      }, self.animationSpeed);
                  }
                  if(this.pushButton == 'on') {
                      $('#responsive-menu-button').css({'transform':''});
                  }
              },
              init: function() {
                  var self = this;
                  $(this.trigger).on(this.triggerTypes, function(e){
                      e.stopPropagation();
                      self.triggerMenu();
                  });
                  $(this.trigger).mouseup(function(){
                      $(self.trigger).blur();
                  });
                  $('.responsive-menu-subarrow').on('click', function(e) {
                      e.preventDefault();
                      e.stopPropagation();
                      self.triggerSubArrow(this);
                  });
                  $(window).resize(function() {
                      if($(window).width() > self.breakpoint) {
                          if(self.isOpen){
                              self.closeMenu();
                          }
                      } else {
                          if($('.responsive-menu-open').length>0){
                              self.setWrapperTranslate();
                          }
                      }
                  });
                  if(this.closeOnLinkClick == 'on') {
                      $(this.linkElement).on('click', function(e) {
                          e.preventDefault();
                          /* Fix for when close menu on parent clicks is on */
                          if(self.itemTriggerSubMenu == 'on' && $(this).is('.responsive-menu-item-has-children > ' + self.linkElement)) {
                              return;
                          }
                          old_href = $(this).attr('href');
                          old_target = typeof $(this).attr('target') == 'undefined' ? '_self' : $(this).attr('target');
                          if(self.isOpen) {
                              if($(e.target).closest('.responsive-menu-subarrow').length) {
                                  return;
                              }
                              self.closeMenu();
                              setTimeout(function() {
                                  window.open(old_href, old_target);
                              }, self.animationSpeed);
                          }
                      });
                  }
                  if(this.closeOnBodyClick == 'on') {
                      $(document).on('click', 'body', function(e) {
                          if(self.isOpen) {
                              if($(e.target).closest('#responsive-menu-container').length || $(e.target).closest('#responsive-menu-button').length) {
                                  return;
                              }
                          }
                          self.closeMenu();
                      });
                  }
                  if(this.itemTriggerSubMenu == 'on') {
                      $('.responsive-menu-item-has-children > ' + this.linkElement).on('click', function(e) {
                          e.preventDefault();
                          self.triggerSubArrow($(this).children('.responsive-menu-subarrow').first());
                      });
                  }
                  if (jQuery('#responsive-menu-button').css('display') != 'none') {
                      $('#responsive-menu-button,#responsive-menu a.responsive-menu-item-link, #responsive-menu-wrapper input').focus( function() {
                          $(this).addClass('is-active');
                          $('html').addClass('responsive-menu-open');
                          $('#responsive-menu li').css({"opacity": "1", "margin-left": "0"});
                      });

                      $('#responsive-menu-button, a.responsive-menu-item-link,#responsive-menu-wrapper input').focusout( function() {
                          if ( $(this).last('#responsive-menu-button a.responsive-menu-item-link') ) {
                              $(this).removeClass('is-active');
                              $('html').removeClass('responsive-menu-open');
                          }
                      });
                  }
                  $('#responsive-menu a.responsive-menu-item-link').keydown(function(event) {
                      console.log( event.keyCode );
                      if ( [13,27,32,35,36,37,38,39,40].indexOf( event.keyCode) == -1) {
                          return;
                      }
                      var link = $(this);
                      switch(event.keyCode) {
                          case 13:
                          link.click();
                              break;
                          case 27:
                          var dropdown = link.parent('li').parents('.responsive-menu-submenu');
                              if ( dropdown.length > 0 ) {
                                  dropdown.hide();
                                  dropdown.prev().focus();
                              }
                              break;

                          case 32:
                          var dropdown = link.parent('li').find('.responsive-menu-submenu');
                              if ( dropdown.length > 0 ) {
                                  dropdown.show();
                                  dropdown.find('a, input, button, textarea').first().focus();
                              }
                              break;

                          case 35:
                          var dropdown = link.parent('li').find('.responsive-menu-submenu');
                              if ( dropdown.length > 0 ) {
                                  dropdown.hide();
                              }
                              $(this).parents('#responsive-menu').find('a.responsive-menu-item-link').filter(':visible').last().focus();
                              break;
                          case 36:
                          var dropdown = link.parent('li').find('.responsive-menu-submenu');
                              if( dropdown.length > 0 ) {
                                  dropdown.hide();
                              }
                              $(this).parents('#responsive-menu').find('a.responsive-menu-item-link').filter(':visible').first().focus();
                              break;
                          case 37:
                          case 38:
                              event.preventDefault();
                              event.stopPropagation();
                              if ( link.parent('li').prevAll('li').filter(':visible').first().length == 0) {
                                  link.parent('li').nextAll('li').filter(':visible').last().find('a').first().focus();
                              } else {
                                  link.parent('li').prevAll('li').filter(':visible').first().find('a').first().focus();
                              }
                              break;
                          case 39:
                          case 40:
                              event.preventDefault();
                              event.stopPropagation();
                              if( link.parent('li').nextAll('li').filter(':visible').first().length == 0) {
                                  link.parent('li').prevAll('li').filter(':visible').last().find('a').first().focus();
                              } else {
                                  link.parent('li').nextAll('li').filter(':visible').first().find('a').first().focus();
                              }
                              break;
                      }
                  });
              }
          };
          ResponsiveMenu.init();
      });});
    </script>

    <style type="text/css" id="wp-custom-css">
        .wp-video {
            width: 100% !important;
        }

        .mejs-container {
            margin: 0 auto;
        }

        #page .team .person .image img {
            max-width: 120px;
        }

        @media screen and (max-width: 799px) {
            #page .traveling .traveling-image img {
                height: 200px;
                width: 83px;
            }
        }
    </style>

    <noscript>
        <style id="rocket-lazyload-nojs-css">
            .rll-youtube-player,
            [data-lazy-src] {
                display: none !important;
            }
        </style>
    </noscript>

    {{-- <meta name="facebook-domain-verification" content="a7q6u5fo2uotasxbcwko16uq3zdera" /> --}}

    <script type="application/ld+json">
      {
        "@context": "http://schema.org",
        "@type": "WebSite",
        "url": "{{ route('index') }}",
        "name": "Well Health Labs",
        "description": "Well Health Labs",
        "publisher": {
          "@type": "DiagnosticLab",
          "name": "Well Health Labs®",
          "@id": "#anylabtestnow",
          "url": "{{ route('index') }}",
          "description": "Well Health Labs",
          "logo": {
            "@type": "ImageObject",
            "name": "Well Health Labs",
            "url": "{{ asset('images/logo.png') }}",
            "width": "222",
            "height": "44"
          },
          "telephone": "+123-123-1234",
          "sameAs": [
            "{{ route('index') }}",
            "{{ route('index') }}",
            "{{ route('index') }}",
            "{{ route('index') }}",
            "{{ route('index') }}"
          ],
          "availableTest": [
            {
              "@type": "MedicalTest",
              "name": "General Health Test",
              "description": "Get an accurate snapshot of your overall health.",
              "url": "{{ route('index') }}"
            },
            {
              "@type": "MedicalTest",
              "name": "DNA Test",
              "description": "Questions about your family? The answers lies in your DNA.",
              "url": "{{ route('index') }}"
            },
            {
              "@type": "MedicalTest",
              "name": "STD Test",
              "description": "Get answers to life's most personal questions.",
              "url": "{{ route('index') }}"
            },
            {
              "@type": "MedicalTest",
              "name": "Drugs and Alcohol Test",
              "description": "Peace of mind for employers, employees, parents and families.",
              "url": "{{ route('index') }}"
            }
          ]
        },
        "potentialAction": {
          "@type": "SearchAction",
          "target": "{{ route('index') }}",
          "query-input": "required name=search_term_string"
        }
      }
    </script>

    <style id='divi-dynamic-critical-inline-css' type='text/css'>

        @media (min-width:981px) {

            .et_pb_gutters3 .et_pb_column,
            .et_pb_gutters3.et_pb_row .et_pb_column {
                margin-right: 5.5%
            }

            .et_pb_gutters3 .et_pb_column_4_4,
            .et_pb_gutters3.et_pb_row .et_pb_column_4_4 {
                width: 100%
            }

            .et_pb_gutters3 .et_pb_column_4_4 .et_pb_module,
            .et_pb_gutters3.et_pb_row .et_pb_column_4_4 .et_pb_module {
                margin-bottom: 2.75%
            }

            .et_pb_gutters3 .et_pb_column_3_4,
            .et_pb_gutters3.et_pb_row .et_pb_column_3_4 {
                width: 73.625%
            }

            .et_pb_gutters3 .et_pb_column_3_4 .et_pb_module,
            .et_pb_gutters3.et_pb_row .et_pb_column_3_4 .et_pb_module {
                margin-bottom: 3.735%
            }

            .et_pb_gutters3 .et_pb_column_2_3,
            .et_pb_gutters3.et_pb_row .et_pb_column_2_3 {
                width: 64.833%
            }

            .et_pb_gutters3 .et_pb_column_2_3 .et_pb_module,
            .et_pb_gutters3.et_pb_row .et_pb_column_2_3 .et_pb_module {
                margin-bottom: 4.242%
            }

            .et_pb_gutters3 .et_pb_column_3_5,
            .et_pb_gutters3.et_pb_row .et_pb_column_3_5 {
                width: 57.8%
            }

            .et_pb_gutters3 .et_pb_column_3_5 .et_pb_module,
            .et_pb_gutters3.et_pb_row .et_pb_column_3_5 .et_pb_module {
                margin-bottom: 4.758%
            }

            .et_pb_gutters3 .et_pb_column_1_2,
            .et_pb_gutters3.et_pb_row .et_pb_column_1_2 {
                width: 47.25%
            }

            .et_pb_gutters3 .et_pb_column_1_2 .et_pb_module,
            .et_pb_gutters3.et_pb_row .et_pb_column_1_2 .et_pb_module {
                margin-bottom: 5.82%
            }

            .et_pb_gutters3 .et_pb_column_2_5,
            .et_pb_gutters3.et_pb_row .et_pb_column_2_5 {
                width: 36.7%
            }

            .et_pb_gutters3 .et_pb_column_2_5 .et_pb_module,
            .et_pb_gutters3.et_pb_row .et_pb_column_2_5 .et_pb_module {
                margin-bottom: 7.493%
            }

            .et_pb_gutters3 .et_pb_column_1_3,
            .et_pb_gutters3.et_pb_row .et_pb_column_1_3 {
                width: 29.6667%
            }

            .et_pb_gutters3 .et_pb_column_1_3 .et_pb_module,
            .et_pb_gutters3.et_pb_row .et_pb_column_1_3 .et_pb_module {
                margin-bottom: 9.27%
            }

            .et_pb_gutters3 .et_pb_column_1_4,
            .et_pb_gutters3.et_pb_row .et_pb_column_1_4 {
                width: 20.875%
            }

            .et_pb_gutters3 .et_pb_column_1_4 .et_pb_module,
            .et_pb_gutters3.et_pb_row .et_pb_column_1_4 .et_pb_module {
                margin-bottom: 13.174%
            }

            .et_pb_gutters3 .et_pb_column_1_5,
            .et_pb_gutters3.et_pb_row .et_pb_column_1_5 {
                width: 15.6%
            }

            .et_pb_gutters3 .et_pb_column_1_5 .et_pb_module,
            .et_pb_gutters3.et_pb_row .et_pb_column_1_5 .et_pb_module {
                margin-bottom: 17.628%
            }

            .et_pb_gutters3 .et_pb_column_1_6,
            .et_pb_gutters3.et_pb_row .et_pb_column_1_6 {
                width: 12.0833%
            }

            .et_pb_gutters3 .et_pb_column_1_6 .et_pb_module,
            .et_pb_gutters3.et_pb_row .et_pb_column_1_6 .et_pb_module {
                margin-bottom: 22.759%
            }

            .et_pb_gutters3 .et_full_width_page.woocommerce-page ul.products li.product {
                width: 20.875%;
                margin-right: 5.5%;
                margin-bottom: 5.5%
            }

            .et_pb_gutters3.et_left_sidebar.woocommerce-page #main-content ul.products li.product,
            .et_pb_gutters3.et_right_sidebar.woocommerce-page #main-content ul.products li.product {
                width: 28.353%;
                margin-right: 7.47%
            }

            .et_pb_gutters3.et_left_sidebar.woocommerce-page #main-content ul.products.columns-1 li.product,
            .et_pb_gutters3.et_right_sidebar.woocommerce-page #main-content ul.products.columns-1 li.product {
                width: 100%;
                margin-right: 0
            }

            .et_pb_gutters3.et_left_sidebar.woocommerce-page #main-content ul.products.columns-2 li.product,
            .et_pb_gutters3.et_right_sidebar.woocommerce-page #main-content ul.products.columns-2 li.product {
                width: 48%;
                margin-right: 4%
            }

            .et_pb_gutters3.et_left_sidebar.woocommerce-page #main-content ul.products.columns-2 li:nth-child(2n+2),
            .et_pb_gutters3.et_right_sidebar.woocommerce-page #main-content ul.products.columns-2 li:nth-child(2n+2) {
                margin-right: 0
            }

            .et_pb_gutters3.et_left_sidebar.woocommerce-page #main-content ul.products.columns-2 li:nth-child(3n+1),
            .et_pb_gutters3.et_right_sidebar.woocommerce-page #main-content ul.products.columns-2 li:nth-child(3n+1) {
                clear: none
            }
        }

        @media (min-width:981px) {
            .et_pb_gutter.et_pb_gutters1 #left-area {
                width: 75%
            }

            .et_pb_gutter.et_pb_gutters1 #sidebar {
                width: 25%
            }

            .et_pb_gutters1.et_right_sidebar #left-area {
                padding-right: 0
            }

            .et_pb_gutters1.et_left_sidebar #left-area {
                padding-left: 0
            }

            .et_pb_gutter.et_pb_gutters1.et_right_sidebar #main-content .container:before {
                right: 25% !important
            }

            .et_pb_gutter.et_pb_gutters1.et_left_sidebar #main-content .container:before {
                left: 25% !important
            }

            .et_pb_gutters1 .et_pb_column,
            .et_pb_gutters1.et_pb_row .et_pb_column {
                margin-right: 0
            }

            .et_pb_gutters1 .et_pb_column_4_4,
            .et_pb_gutters1.et_pb_row .et_pb_column_4_4 {
                width: 100%
            }

            .et_pb_gutters1 .et_pb_column_4_4 .et_pb_module,
            .et_pb_gutters1.et_pb_row .et_pb_column_4_4 .et_pb_module {
                margin-bottom: 0
            }

            .et_pb_gutters1 .et_pb_column_3_4,
            .et_pb_gutters1.et_pb_row .et_pb_column_3_4 {
                width: 75%
            }

            .et_pb_gutters1 .et_pb_column_3_4 .et_pb_module,
            .et_pb_gutters1.et_pb_row .et_pb_column_3_4 .et_pb_module {
                margin-bottom: 0
            }

            .et_pb_gutters1 .et_pb_column_2_3,
            .et_pb_gutters1.et_pb_row .et_pb_column_2_3 {
                width: 66.667%
            }

            .et_pb_gutters1 .et_pb_column_2_3 .et_pb_module,
            .et_pb_gutters1.et_pb_row .et_pb_column_2_3 .et_pb_module {
                margin-bottom: 0
            }

            .et_pb_gutters1 .et_pb_column_3_5,
            .et_pb_gutters1.et_pb_row .et_pb_column_3_5 {
                width: 60%
            }

            .et_pb_gutters1 .et_pb_column_3_5 .et_pb_module,
            .et_pb_gutters1.et_pb_row .et_pb_column_3_5 .et_pb_module {
                margin-bottom: 0
            }

            .et_pb_gutters1 .et_pb_column_1_2,
            .et_pb_gutters1.et_pb_row .et_pb_column_1_2 {
                width: 50%
            }

            .et_pb_gutters1 .et_pb_column_1_2 .et_pb_module,
            .et_pb_gutters1.et_pb_row .et_pb_column_1_2 .et_pb_module {
                margin-bottom: 0
            }

            .et_pb_gutters1 .et_pb_column_2_5,
            .et_pb_gutters1.et_pb_row .et_pb_column_2_5 {
                width: 40%
            }

            .et_pb_gutters1 .et_pb_column_2_5 .et_pb_module,
            .et_pb_gutters1.et_pb_row .et_pb_column_2_5 .et_pb_module {
                margin-bottom: 0
            }

            .et_pb_gutters1 .et_pb_column_1_3,
            .et_pb_gutters1.et_pb_row .et_pb_column_1_3 {
                width: 33.3333%
            }

            .et_pb_gutters1 .et_pb_column_1_3 .et_pb_module,
            .et_pb_gutters1.et_pb_row .et_pb_column_1_3 .et_pb_module {
                margin-bottom: 0
            }

            .et_pb_gutters1 .et_pb_column_1_4,
            .et_pb_gutters1.et_pb_row .et_pb_column_1_4 {
                width: 25%
            }

            .et_pb_gutters1 .et_pb_column_1_4 .et_pb_module,
            .et_pb_gutters1.et_pb_row .et_pb_column_1_4 .et_pb_module {
                margin-bottom: 0
            }

            .et_pb_gutters1 .et_pb_column_1_5,
            .et_pb_gutters1.et_pb_row .et_pb_column_1_5 {
                width: 20%
            }

            .et_pb_gutters1 .et_pb_column_1_5 .et_pb_module,
            .et_pb_gutters1.et_pb_row .et_pb_column_1_5 .et_pb_module {
                margin-bottom: 0
            }

            .et_pb_gutters1 .et_pb_column_1_6,
            .et_pb_gutters1.et_pb_row .et_pb_column_1_6 {
                width: 16.6667%
            }

            .et_pb_gutters1 .et_pb_column_1_6 .et_pb_module,
            .et_pb_gutters1.et_pb_row .et_pb_column_1_6 .et_pb_module {
                margin-bottom: 0
            }

            .et_pb_gutters1 .et_full_width_page.woocommerce-page ul.products li.product {
                width: 25%;
                margin-right: 0;
                margin-bottom: 0
            }

            .et_pb_gutters1.et_left_sidebar.woocommerce-page #main-content ul.products li.product,
            .et_pb_gutters1.et_right_sidebar.woocommerce-page #main-content ul.products li.product {
                width: 33.333%;
                margin-right: 0
            }
        }

        @media (max-width:980px) {

            .et_pb_gutters1 .et_pb_column,
            .et_pb_gutters1 .et_pb_column .et_pb_module,
            .et_pb_gutters1.et_pb_row .et_pb_column,
            .et_pb_gutters1.et_pb_row .et_pb_column .et_pb_module {
                margin-bottom: 0
            }

            .et_pb_gutters1 .et_pb_row_1-2_1-4_1-4>.et_pb_column.et_pb_column_1_4,
            .et_pb_gutters1 .et_pb_row_1-4_1-4>.et_pb_column.et_pb_column_1_4,
            .et_pb_gutters1 .et_pb_row_1-4_1-4_1-2>.et_pb_column.et_pb_column_1_4,
            .et_pb_gutters1 .et_pb_row_1-5_1-5_3-5>.et_pb_column.et_pb_column_1_5,
            .et_pb_gutters1 .et_pb_row_3-5_1-5_1-5>.et_pb_column.et_pb_column_1_5,
            .et_pb_gutters1 .et_pb_row_4col>.et_pb_column.et_pb_column_1_4,
            .et_pb_gutters1 .et_pb_row_5col>.et_pb_column.et_pb_column_1_5,
            .et_pb_gutters1.et_pb_row_1-2_1-4_1-4>.et_pb_column.et_pb_column_1_4,
            .et_pb_gutters1.et_pb_row_1-4_1-4>.et_pb_column.et_pb_column_1_4,
            .et_pb_gutters1.et_pb_row_1-4_1-4_1-2>.et_pb_column.et_pb_column_1_4,
            .et_pb_gutters1.et_pb_row_1-5_1-5_3-5>.et_pb_column.et_pb_column_1_5,
            .et_pb_gutters1.et_pb_row_3-5_1-5_1-5>.et_pb_column.et_pb_column_1_5,
            .et_pb_gutters1.et_pb_row_4col>.et_pb_column.et_pb_column_1_4,
            .et_pb_gutters1.et_pb_row_5col>.et_pb_column.et_pb_column_1_5 {
                width: 50%;
                margin-right: 0
            }

            .et_pb_gutters1 .et_pb_row_1-2_1-6_1-6_1-6>.et_pb_column.et_pb_column_1_6,
            .et_pb_gutters1 .et_pb_row_1-6_1-6_1-6>.et_pb_column.et_pb_column_1_6,
            .et_pb_gutters1 .et_pb_row_1-6_1-6_1-6_1-2>.et_pb_column.et_pb_column_1_6,
            .et_pb_gutters1 .et_pb_row_6col>.et_pb_column.et_pb_column_1_6,
            .et_pb_gutters1.et_pb_row_1-2_1-6_1-6_1-6>.et_pb_column.et_pb_column_1_6,
            .et_pb_gutters1.et_pb_row_1-6_1-6_1-6>.et_pb_column.et_pb_column_1_6,
            .et_pb_gutters1.et_pb_row_1-6_1-6_1-6_1-2>.et_pb_column.et_pb_column_1_6,
            .et_pb_gutters1.et_pb_row_6col>.et_pb_column.et_pb_column_1_6 {
                width: 33.333%;
                margin-right: 0
            }

            .et_pb_gutters1 .et_pb_row_1-6_1-6_1-6_1-6>.et_pb_column.et_pb_column_1_6,
            .et_pb_gutters1.et_pb_row_1-6_1-6_1-6_1-6>.et_pb_column.et_pb_column_1_6 {
                width: 50%;
                margin-right: 0
            }
        }

        @media (max-width:767px) {

            .et_pb_gutters1 .et_pb_column,
            .et_pb_gutters1 .et_pb_column .et_pb_module,
            .et_pb_gutters1.et_pb_row .et_pb_column,
            .et_pb_gutters1.et_pb_row .et_pb_column .et_pb_module {
                margin-bottom: 0
            }
        }

        @media (max-width:479px) {

            .et_pb_gutters1 .et_pb_column,
            .et_pb_gutters1.et_pb_row .et_pb_column {
                margin: 0 !important
            }

            .et_pb_gutters1 .et_pb_column .et_pb_module,
            .et_pb_gutters1.et_pb_row .et_pb_column .et_pb_module {
                margin-bottom: 0
            }
        }

        @media (min-width:981px) {
            .et_pb_gutter.et_pb_gutters2 #left-area {
                width: 77.25%
            }

            .et_pb_gutter.et_pb_gutters2 #sidebar {
                width: 22.75%
            }

            .et_pb_gutters2.et_right_sidebar #left-area {
                padding-right: 3%
            }

            .et_pb_gutters2.et_left_sidebar #left-area {
                padding-left: 3%
            }

            .et_pb_gutter.et_pb_gutters2.et_right_sidebar #main-content .container:before {
                right: 22.75% !important
            }

            .et_pb_gutter.et_pb_gutters2.et_left_sidebar #main-content .container:before {
                left: 22.75% !important
            }

            .et_pb_gutters2 .et_pb_column,
            .et_pb_gutters2.et_pb_row .et_pb_column {
                margin-right: 3%
            }

            .et_pb_gutters2 .et_pb_column_4_4,
            .et_pb_gutters2.et_pb_row .et_pb_column_4_4 {
                width: 100%
            }

            .et_pb_gutters2 .et_pb_column_4_4 .et_pb_module,
            .et_pb_gutters2.et_pb_row .et_pb_column_4_4 .et_pb_module {
                margin-bottom: 1.5%
            }

            .et_pb_gutters2 .et_pb_column_3_4,
            .et_pb_gutters2.et_pb_row .et_pb_column_3_4 {
                width: 74.25%
            }

            .et_pb_gutters2 .et_pb_column_3_4 .et_pb_module,
            .et_pb_gutters2.et_pb_row .et_pb_column_3_4 .et_pb_module {
                margin-bottom: 2.02%
            }

            .et_pb_gutters2 .et_pb_column_2_3,
            .et_pb_gutters2.et_pb_row .et_pb_column_2_3 {
                width: 65.667%
            }

            .et_pb_gutters2 .et_pb_column_2_3 .et_pb_module,
            .et_pb_gutters2.et_pb_row .et_pb_column_2_3 .et_pb_module {
                margin-bottom: 2.284%
            }

            .et_pb_gutters2 .et_pb_column_3_5,
            .et_pb_gutters2.et_pb_row .et_pb_column_3_5 {
                width: 58.8%
            }

            .et_pb_gutters2 .et_pb_column_3_5 .et_pb_module,
            .et_pb_gutters2.et_pb_row .et_pb_column_3_5 .et_pb_module {
                margin-bottom: 2.551%
            }

            .et_pb_gutters2 .et_pb_column_1_2,
            .et_pb_gutters2.et_pb_row .et_pb_column_1_2 {
                width: 48.5%
            }

            .et_pb_gutters2 .et_pb_column_1_2 .et_pb_module,
            .et_pb_gutters2.et_pb_row .et_pb_column_1_2 .et_pb_module {
                margin-bottom: 3.093%
            }

            .et_pb_gutters2 .et_pb_column_2_5,
            .et_pb_gutters2.et_pb_row .et_pb_column_2_5 {
                width: 38.2%
            }

            .et_pb_gutters2 .et_pb_column_2_5 .et_pb_module,
            .et_pb_gutters2.et_pb_row .et_pb_column_2_5 .et_pb_module {
                margin-bottom: 3.927%
            }

            .et_pb_gutters2 .et_pb_column_1_3,
            .et_pb_gutters2.et_pb_row .et_pb_column_1_3 {
                width: 31.3333%
            }

            .et_pb_gutters2 .et_pb_column_1_3 .et_pb_module,
            .et_pb_gutters2.et_pb_row .et_pb_column_1_3 .et_pb_module {
                margin-bottom: 4.787%
            }

            .et_pb_gutters2 .et_pb_column_1_4,
            .et_pb_gutters2.et_pb_row .et_pb_column_1_4 {
                width: 22.75%
            }

            .et_pb_gutters2 .et_pb_column_1_4 .et_pb_module,
            .et_pb_gutters2.et_pb_row .et_pb_column_1_4 .et_pb_module {
                margin-bottom: 6.593%
            }

            .et_pb_gutters2 .et_pb_column_1_5,
            .et_pb_gutters2.et_pb_row .et_pb_column_1_5 {
                width: 17.6%
            }

            .et_pb_gutters2 .et_pb_column_1_5 .et_pb_module,
            .et_pb_gutters2.et_pb_row .et_pb_column_1_5 .et_pb_module {
                margin-bottom: 8.523%
            }

            .et_pb_gutters2 .et_pb_column_1_6,
            .et_pb_gutters2.et_pb_row .et_pb_column_1_6 {
                width: 14.1667%
            }

            .et_pb_gutters2 .et_pb_column_1_6 .et_pb_module,
            .et_pb_gutters2.et_pb_row .et_pb_column_1_6 .et_pb_module {
                margin-bottom: 10.588%
            }

            .et_pb_gutters2 .et_full_width_page.woocommerce-page ul.products li.product {
                width: 22.75%;
                margin-right: 3%;
                margin-bottom: 3%
            }

            .et_pb_gutters2.et_left_sidebar.woocommerce-page #main-content ul.products li.product,
            .et_pb_gutters2.et_right_sidebar.woocommerce-page #main-content ul.products li.product {
                width: 30.64%;
                margin-right: 4.04%
            }
        }


        @media (min-width:981px) {
            .et_pb_section {
                padding: 4% 0
            }

            body.et_pb_pagebuilder_layout.et_pb_show_title .post-password-required .et_pb_section,
            body:not(.et_pb_pagebuilder_layout) .post-password-required .et_pb_section {
                padding-top: 0
            }

            .et_pb_fullwidth_section {
                padding: 0
            }

            .et_pb_section_video_bg.et_pb_section_video_bg_desktop_only {
                display: block
            }
        }

        @media (max-width:980px) {
            .et_pb_section {
                padding: 50px 0
            }

            body.et_pb_pagebuilder_layout.et_pb_show_title .post-password-required .et_pb_section,
            body:not(.et_pb_pagebuilder_layout) .post-password-required .et_pb_section {
                padding-top: 0
            }

            .et_pb_fullwidth_section {
                padding: 0
            }

            .et_pb_section_video_bg.et_pb_section_video_bg_tablet {
                display: block
            }

            .et_pb_section_video_bg.et_pb_section_video_bg_desktop_only {
                display: none
            }
        }

        @media (min-width:768px) {
            .et_pb_section_video_bg.et_pb_section_video_bg_desktop_tablet {
                display: block
            }
        }

        @media (min-width:768px) and (max-width:980px) {
            .et_pb_section_video_bg.et_pb_section_video_bg_tablet_only {
                display: block
            }
        }

        @media (max-width:767px) {
            .et_pb_section_video_bg.et_pb_section_video_bg_phone {
                display: block
            }

            .et_pb_section_video_bg.et_pb_section_video_bg_desktop_tablet {
                display: none
            }
        }

        .et_pb_row {
            width: 80%;
            max-width: 1080px;
            margin: auto;
            position: relative
        }

        body.safari .section_has_divider,
        body.uiwebview .section_has_divider {
            perspective: 2000px
        }

        .section_has_divider .et_pb_row {
            z-index: 5
        }

        .et_pb_row_inner {
            width: 100%;
            position: relative
        }

        .et_pb_row.et_pb_row_empty,
        .et_pb_row_inner:nth-of-type(n+2).et_pb_row_empty {
            display: none
        }

        .et_pb_row:after,
        .et_pb_row_inner:after {
            content: "";
            display: block;
            clear: both;
            visibility: hidden;
            line-height: 0;
            height: 0;
            width: 0
        }

        .et_pb_row_4col .et-last-child,
        .et_pb_row_4col .et-last-child-2,
        .et_pb_row_6col .et-last-child,
        .et_pb_row_6col .et-last-child-2,
        .et_pb_row_6col .et-last-child-3 {
            margin-bottom: 0
        }

        .et_pb_column {
            float: left;
            background-size: cover;
            background-position: 50%;
            position: relative;
            z-index: 2;
            min-height: 1px
        }

        .et_pb_column--with-menu {
            z-index: 3
        }

        .et_pb_column.et_pb_column_empty {
            min-height: 1px
        }

        .et_pb_row .et_pb_column.et-last-child,
        .et_pb_row .et_pb_column:last-child,
        .et_pb_row_inner .et_pb_column.et-last-child,
        .et_pb_row_inner .et_pb_column:last-child {
            margin-right: 0 !important
        }

        .et_pb_column.et_pb_section_parallax {
            position: relative
        }

        .et_pb_column,
        .et_pb_row,
        .et_pb_row_inner {
            background-size: cover;
            background-position: 50%;
            background-repeat: no-repeat
        }

        @media (min-width:981px) {
            .et_pb_row {
                padding: 2% 0
            }

            body.et_pb_pagebuilder_layout.et_pb_show_title .post-password-required .et_pb_row,
            body:not(.et_pb_pagebuilder_layout) .post-password-required .et_pb_row {
                padding: 0;
                width: 100%
            }

            .et_pb_column_3_4 .et_pb_row_inner {
                padding: 3.735% 0
            }

            .et_pb_column_2_3 .et_pb_row_inner {
                padding: 4.2415% 0
            }

            .et_pb_column_1_2 .et_pb_row_inner,
            .et_pb_column_3_5 .et_pb_row_inner {
                padding: 5.82% 0
            }

            .et_section_specialty>.et_pb_row {
                padding: 0
            }

            .et_pb_row_inner {
                width: 100%
            }

            .et_pb_column_single {
                padding: 2.855% 0
            }

            .et_pb_column_single .et_pb_module.et-first-child,
            .et_pb_column_single .et_pb_module:first-child {
                margin-top: 0
            }

            .et_pb_column_single .et_pb_module.et-last-child,
            .et_pb_column_single .et_pb_module:last-child {
                margin-bottom: 0
            }

            .et_pb_row .et_pb_column.et-last-child,
            .et_pb_row .et_pb_column:last-child,
            .et_pb_row_inner .et_pb_column.et-last-child,
            .et_pb_row_inner .et_pb_column:last-child {
                margin-right: 0 !important
            }

            .et_pb_row.et_pb_equal_columns,
            .et_pb_row_inner.et_pb_equal_columns,
            .et_pb_section.et_pb_equal_columns>.et_pb_row {
                display: -ms-flexbox;
                display: flex;
                direction: ltr
            }

            .et_pb_row.et_pb_equal_columns>.et_pb_column,
            .et_pb_section.et_pb_equal_columns>.et_pb_row>.et_pb_column {
                -ms-flex-order: 1;
                order: 1
            }
        }

        @media (max-width:980px) {
            .et_pb_row {
                max-width: 1080px
            }

            body.et_pb_pagebuilder_layout.et_pb_show_title .post-password-required .et_pb_row,
            body:not(.et_pb_pagebuilder_layout) .post-password-required .et_pb_row {
                padding: 0;
                width: 100%
            }

            .et_pb_column .et_pb_row_inner,
            .et_pb_row {
                padding: 30px 0
            }

            .et_section_specialty>.et_pb_row {
                padding: 0
            }

            .et_pb_column {
                width: 100%;
                margin-bottom: 30px
            }

            .et_pb_bottom_divider .et_pb_row:nth-last-child(2) .et_pb_column:last-child,
            .et_pb_row .et_pb_column.et-last-child,
            .et_pb_row .et_pb_column:last-child {
                margin-bottom: 0
            }

            .et_section_specialty .et_pb_row>.et_pb_column {
                padding-bottom: 0
            }

            .et_pb_column.et_pb_column_empty {
                display: none
            }

            .et_pb_row_1-2_1-4_1-4,
            .et_pb_row_1-2_1-6_1-6_1-6,
            .et_pb_row_1-4_1-4,
            .et_pb_row_1-4_1-4_1-2,
            .et_pb_row_1-5_1-5_3-5,
            .et_pb_row_1-6_1-6_1-6,
            .et_pb_row_1-6_1-6_1-6_1-2,
            .et_pb_row_1-6_1-6_1-6_1-6,
            .et_pb_row_3-5_1-5_1-5,
            .et_pb_row_4col,
            .et_pb_row_5col,
            .et_pb_row_6col {
                display: -ms-flexbox;
                display: flex;
                -ms-flex-wrap: wrap;
                flex-wrap: wrap
            }

            .et_pb_row_1-4_1-4>.et_pb_column.et_pb_column_1_4,
            .et_pb_row_1-4_1-4_1-2>.et_pb_column.et_pb_column_1_4,
            .et_pb_row_4col>.et_pb_column.et_pb_column_1_4 {
                width: 47.25%;
                margin-right: 5.5%
            }

            .et_pb_row_1-4_1-4>.et_pb_column.et_pb_column_1_4:nth-child(2n),
            .et_pb_row_1-4_1-4_1-2>.et_pb_column.et_pb_column_1_4:nth-child(2n),
            .et_pb_row_4col>.et_pb_column.et_pb_column_1_4:nth-child(2n) {
                margin-right: 0
            }

            .et_pb_row_1-2_1-4_1-4>.et_pb_column.et_pb_column_1_4 {
                width: 47.25%;
                margin-right: 5.5%
            }

            .et_pb_row_1-2_1-4_1-4>.et_pb_column.et_pb_column_1_2,
            .et_pb_row_1-2_1-4_1-4>.et_pb_column.et_pb_column_1_4:nth-child(odd) {
                margin-right: 0
            }

            .et_pb_row_1-2_1-4_1-4 .et_pb_column:nth-last-child(-n+2),
            .et_pb_row_1-4_1-4 .et_pb_column:nth-last-child(-n+2),
            .et_pb_row_4col .et_pb_column:nth-last-child(-n+2) {
                margin-bottom: 0
            }

            .et_pb_row_1-5_1-5_3-5>.et_pb_column.et_pb_column_1_5,
            .et_pb_row_5col>.et_pb_column.et_pb_column_1_5 {
                width: 47.25%;
                margin-right: 5.5%
            }

            .et_pb_row_1-5_1-5_3-5>.et_pb_column.et_pb_column_1_5:nth-child(2n),
            .et_pb_row_5col>.et_pb_column.et_pb_column_1_5:nth-child(2n) {
                margin-right: 0
            }

            .et_pb_row_3-5_1-5_1-5>.et_pb_column.et_pb_column_1_5 {
                width: 47.25%;
                margin-right: 5.5%
            }

            .et_pb_row_3-5_1-5_1-5>.et_pb_column.et_pb_column_1_5:nth-child(odd),
            .et_pb_row_3-5_1-5_1-5>.et_pb_column.et_pb_column_3_5 {
                margin-right: 0
            }

            .et_pb_row_3-5_1-5_1-5 .et_pb_column:nth-last-child(-n+2),
            .et_pb_row_5col .et_pb_column:last-child {
                margin-bottom: 0
            }

            .et_pb_row_1-6_1-6_1-6_1-2>.et_pb_column.et_pb_column_1_6,
            .et_pb_row_6col>.et_pb_column.et_pb_column_1_6 {
                width: 29.666%;
                margin-right: 5.5%
            }

            .et_pb_row_1-6_1-6_1-6_1-2>.et_pb_column.et_pb_column_1_6:nth-child(3n),
            .et_pb_row_6col>.et_pb_column.et_pb_column_1_6:nth-child(3n) {
                margin-right: 0
            }

            .et_pb_row_1-2_1-6_1-6_1-6>.et_pb_column.et_pb_column_1_6 {
                width: 29.666%;
                margin-right: 5.5%
            }

            .et_pb_row_1-2_1-6_1-6_1-6>.et_pb_column.et_pb_column_1_2,
            .et_pb_row_1-2_1-6_1-6_1-6>.et_pb_column.et_pb_column_1_6:last-child {
                margin-right: 0
            }

            .et_pb_row_1-2_1-2 .et_pb_column.et_pb_column_1_2,
            .et_pb_row_1-2_1-6_1-6_1-6 .et_pb_column:nth-last-child(-n+3),
            .et_pb_row_6col .et_pb_column:nth-last-child(-n+3) {
                margin-bottom: 0
            }

            .et_pb_row_1-2_1-2 .et_pb_column.et_pb_column_1_2 .et_pb_column.et_pb_column_1_6 {
                width: 29.666%;
                margin-right: 5.5%;
                margin-bottom: 0
            }

            .et_pb_row_1-2_1-2 .et_pb_column.et_pb_column_1_2 .et_pb_column.et_pb_column_1_6:last-child {
                margin-right: 0
            }

            .et_pb_row_1-6_1-6_1-6_1-6>.et_pb_column.et_pb_column_1_6 {
                width: 47.25%;
                margin-right: 5.5%
            }

            .et_pb_row_1-6_1-6_1-6_1-6>.et_pb_column.et_pb_column_1_6:nth-child(2n) {
                margin-right: 0
            }

            .et_pb_row_1-6_1-6_1-6_1-6:nth-last-child(-n+3) {
                margin-bottom: 0
            }
        }

        @media (max-width:479px) {

            .et_pb_row .et_pb_column.et_pb_column_1_4,
            .et_pb_row .et_pb_column.et_pb_column_1_5,
            .et_pb_row .et_pb_column.et_pb_column_1_6 {
                width: 100%;
                margin: 0 0 30px
            }

            .et_pb_row .et_pb_column.et_pb_column_1_4.et-last-child,
            .et_pb_row .et_pb_column.et_pb_column_1_4:last-child,
            .et_pb_row .et_pb_column.et_pb_column_1_5.et-last-child,
            .et_pb_row .et_pb_column.et_pb_column_1_5:last-child,
            .et_pb_row .et_pb_column.et_pb_column_1_6.et-last-child,
            .et_pb_row .et_pb_column.et_pb_column_1_6:last-child {
                margin-bottom: 0
            }

            .et_pb_row_1-2_1-2 .et_pb_column.et_pb_column_1_2 .et_pb_column.et_pb_column_1_6 {
                width: 100%;
                margin: 0 0 30px
            }

            .et_pb_row_1-2_1-2 .et_pb_column.et_pb_column_1_2 .et_pb_column.et_pb_column_1_6.et-last-child,
            .et_pb_row_1-2_1-2 .et_pb_column.et_pb_column_1_2 .et_pb_column.et_pb_column_1_6:last-child {
                margin-bottom: 0
            }

            .et_pb_column {
                width: 100% !important
            }
        }

        .et_pb_with_border .et_pb_image_wrap {
            border: 0 solid #333
        }

        .et_pb_image {
            margin-left: auto;
            margin-right: auto;
            line-height: 0
        }

        .et_pb_image.aligncenter {
            text-align: center
        }

        .et_pb_image.et_pb_has_overlay a.et_pb_lightbox_image {
            display: block;
            position: relative
        }

        .et_pb_image {
            display: block
        }

        .et_pb_image .et_pb_image_wrap {
            display: inline-block;
            position: relative;
            max-width: 100%
        }

        .et_pb_image .et_pb_image_wrap img[src*=".svg"] {
            width: auto
        }

        .et_pb_image img {
            position: relative
        }

        .et_pb_image_sticky {
            margin-bottom: 0 !important;
            display: inherit
        }

        .et_pb_image.et_pb_has_overlay .et_pb_image_wrap:hover .et_overlay {
            z-index: 3;
            opacity: 1
        }

        @media (min-width:981px) {

            .et_pb_section_sticky,
            .et_pb_section_sticky.et_pb_bottom_divider .et_pb_row:nth-last-child(2),
            .et_pb_section_sticky .et_pb_column_single,
            .et_pb_section_sticky .et_pb_row.et-last-child,
            .et_pb_section_sticky .et_pb_row:last-child,
            .et_pb_section_sticky .et_pb_specialty_column .et_pb_row_inner.et-last-child,
            .et_pb_section_sticky .et_pb_specialty_column .et_pb_row_inner:last-child {
                padding-bottom: 0 !important
            }
        }

        @media (max-width:980px) {
            .et_pb_image_sticky_tablet {
                margin-bottom: 0 !important;
                display: inherit
            }

            .et_pb_section_sticky_mobile,
            .et_pb_section_sticky_mobile.et_pb_bottom_divider .et_pb_row:nth-last-child(2),
            .et_pb_section_sticky_mobile .et_pb_column_single,
            .et_pb_section_sticky_mobile .et_pb_row.et-last-child,
            .et_pb_section_sticky_mobile .et_pb_row:last-child,
            .et_pb_section_sticky_mobile .et_pb_specialty_column .et_pb_row_inner.et-last-child,
            .et_pb_section_sticky_mobile .et_pb_specialty_column .et_pb_row_inner:last-child {
                padding-bottom: 0 !important
            }

            .et_pb_section_sticky .et_pb_row.et-last-child .et_pb_column.et_pb_row_sticky.et-last-child,
            .et_pb_section_sticky .et_pb_row:last-child .et_pb_column.et_pb_row_sticky:last-child {
                margin-bottom: 0
            }

            .et_pb_image_bottom_space_tablet {
                margin-bottom: 30px !important;
                display: block
            }

            .et_always_center_on_mobile {
                text-align: center !important;
                margin-left: auto !important;
                margin-right: auto !important
            }
        }

        @media (max-width:767px) {
            .et_pb_image_sticky_phone {
                margin-bottom: 0 !important;
                display: inherit
            }

            .et_pb_image_bottom_space_phone {
                margin-bottom: 30px !important;
                display: block
            }
        }

        .et_overlay {
            z-index: -1;
            position: absolute;
            top: 0;
            left: 0;
            display: block;
            width: 100%;
            height: 100%;
            background: hsla(0, 0%, 100%, .9);
            opacity: 0;
            pointer-events: none;
            transition: all .3s;
            border: 1px solid #e5e5e5;
            box-sizing: border-box;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            -webkit-font-smoothing: antialiased
        }

        .et_overlay:before {
            color: #2ea3f2;
            content: "\E050";
            position: absolute;
            top: 55%;
            left: 50%;
            margin: -16px 0 0 -16px;
            font-size: 32px;
            transition: all .4s
        }

        .et_portfolio_image,
        .et_shop_image {
            position: relative;
            display: block
        }

        .et_pb_has_overlay:not(.et_pb_image):hover .et_overlay,
        .et_portfolio_image:hover .et_overlay,
        .et_shop_image:hover .et_overlay {
            z-index: 3;
            opacity: 1
        }

        #ie7 .et_overlay,
        #ie8 .et_overlay {
            display: none
        }

        .et_pb_module.et_pb_has_overlay {
            position: relative
        }

        .et_pb_module.et_pb_has_overlay .et_overlay,
        article.et_pb_has_overlay {
            border: none
        }

        .et-menu li {
            display: inline-block;
            font-size: 14px;
            padding-right: 22px
        }

        .et-menu>li:last-child {
            padding-right: 0
        }

        .et-menu a {
            color: rgba(0, 0, 0, .6);
            text-decoration: none;
            display: block;
            position: relative
        }

        .et-menu a,
        .et-menu a:hover {
            transition: all .4s ease-in-out
        }

        .et-menu a:hover {
            opacity: .7
        }

        .et-menu li>a {
            padding-bottom: 29px;
            word-wrap: break-word
        }

        a.et_pb_menu__icon,
        button.et_pb_menu__icon {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-align: center;
            align-items: center;
            padding: 0;
            margin: 0 11px;
            font-family: ETmodules;
            font-size: 17px;
            background: none;
            border: 0;
            cursor: pointer
        }

        .et_pb_menu__wrap .mobile_menu_bar {
            transform: translateY(3%)
        }

        .et_pb_menu__wrap .mobile_menu_bar:before {
            top: 0
        }

        .et_pb_menu__logo {
            overflow: hidden
        }

        .et_pb_menu__logo img {
            display: block
        }

        .et_pb_menu__logo img[src$=".svg"] {
            width: 100%
        }

        .et_pb_menu__search-button:after {
            content: "U"
        }

        .et_pb_menu__cart-button:after {
            content: "\E07A"
        }

        @media (max-width:980px) {
            .et-menu {
                display: none
            }

            .et_mobile_nav_menu {
                display: block;
                margin-top: -1px
            }
        }

        .et_pb_with_border.et_pb_menu .et_pb_menu__logo img {
            border: 0 solid #333
        }

        .et_pb_menu.et_hover_enabled:hover {
            z-index: auto
        }

        .et_pb_menu .et-menu-nav,
        .et_pb_menu .et-menu-nav>ul {
            float: none
        }

        .et_pb_menu .et-menu-nav>ul {
            padding: 0 !important;
            line-height: 1.7em
        }

        .et_pb_menu .et-menu-nav>ul ul {
            padding: 20px 0;
            text-align: left
        }

        .et_pb_bg_layout_dark.et_pb_menu ul li a {
            color: #fff
        }

        .et_pb_bg_layout_dark.et_pb_menu ul li a:hover {
            color: hsla(0, 0%, 100%, .8)
        }

        .et-menu li li.menu-item-has-children>a:first-child:after {
            top: 12px
        }

        .et_pb_menu .et-menu-nav>ul.upwards li ul {
            bottom: 100%;
            top: auto;
            border-bottom-width: 3px;
            border-bottom-style: solid;
            border-top: none;
            border-bottom-color: #2ea3f2;
            box-shadow: 2px -2px 5px rgba(0, 0, 0, .1)
        }

        .et_pb_menu .et-menu-nav>ul.upwards li ul li ul {
            bottom: -23px
        }

        .et_pb_menu .et-menu-nav>ul.upwards li.mega-menu ul ul {
            bottom: 0;
            top: auto;
            border: none
        }

        .et_pb_menu_inner_container {
            position: relative
        }

        .et_pb_menu .et_pb_menu__wrap {
            -ms-flex: 1 1 auto;
            flex: 1 1 auto;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-pack: start;
            justify-content: flex-start;
            -ms-flex-align: stretch;
            align-items: stretch;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            opacity: 1
        }

        .et_pb_menu .et_pb_menu__wrap--visible {
            animation: fadeInBottom 1s 1 cubic-bezier(.77, 0, .175, 1)
        }

        .et_pb_menu .et_pb_menu__wrap--hidden {
            opacity: 0;
            animation: fadeOutBottom 1s 1 cubic-bezier(.77, 0, .175, 1)
        }

        .et_pb_menu .et_pb_menu__menu {
            -ms-flex: 0 1 auto;
            flex: 0 1 auto;
            -ms-flex-pack: start;
            justify-content: flex-start
        }

        .et_pb_menu .et_pb_menu__menu,
        .et_pb_menu .et_pb_menu__menu>nav,
        .et_pb_menu .et_pb_menu__menu>nav>ul {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-align: stretch;
            align-items: stretch
        }

        .et_pb_menu .et_pb_menu__menu>nav>ul {
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            -ms-flex-pack: start;
            justify-content: flex-start
        }

        .et_pb_menu .et_pb_menu__menu>nav>ul>li {
            position: relative;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-align: stretch;
            align-items: stretch;
            margin: 0
        }

        .et_pb_menu .et_pb_menu__menu>nav>ul>li.mega-menu {
            position: static
        }

        .et_pb_menu .et_pb_menu__menu>nav>ul>li>ul {
            top: calc(100% - 1px);
            left: 0
        }

        .et_pb_menu .et_pb_menu__menu>nav>ul.upwards>li>ul {
            top: auto;
            bottom: calc(100% - 1px)
        }

        .et_pb_menu--with-logo .et_pb_menu__menu>nav>ul>li>a {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-align: center;
            align-items: center;
            padding: 31px 0;
            white-space: nowrap
        }

        .et_pb_menu--with-logo .et_pb_menu__menu>nav>ul>li>a:after {
            top: 50% !important;
            transform: translateY(-50%)
        }

        .et_pb_menu--without-logo .et_pb_menu__menu>nav>ul {
            padding: 0 !important
        }

        .et_pb_menu--without-logo .et_pb_menu__menu>nav>ul>li {
            margin-top: 8px
        }

        .et_pb_menu--without-logo .et_pb_menu__menu>nav>ul>li>a {
            padding-bottom: 8px
        }

        .et_pb_menu--without-logo .et_pb_menu__menu>nav>ul.upwards>li {
            margin-top: 0;
            margin-bottom: 8px
        }

        .et_pb_menu--without-logo .et_pb_menu__menu>nav>ul.upwards>li>a {
            padding-top: 8px;
            padding-bottom: 0
        }

        .et_pb_menu--without-logo .et_pb_menu__menu>nav>ul.upwards>li>a:after {
            top: auto;
            bottom: 0
        }

        .et_pb_menu .et_pb_menu__icon {
            -ms-flex: 0 0 auto;
            flex: 0 0 auto
        }

        .et_pb_menu .et-menu {
            margin-left: -11px;
            margin-right: -11px
        }

        .et_pb_menu .et-menu>li {
            padding-left: 11px;
            padding-right: 11px
        }

        .et_pb_menu--style-left_aligned .et_pb_menu_inner_container,
        .et_pb_menu--style-left_aligned .et_pb_row {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-align: stretch;
            align-items: stretch
        }

        .et_pb_menu--style-left_aligned .et_pb_menu__logo-wrap {
            -ms-flex: 0 1 auto;
            flex: 0 1 auto;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-align: center;
            align-items: center
        }

        .et_pb_menu--style-left_aligned .et_pb_menu__logo {
            margin-right: 30px
        }

        .rtl .et_pb_menu--style-left_aligned .et_pb_menu__logo {
            margin-right: 0;
            margin-left: 30px
        }

        .et_pb_menu--style-left_aligned.et_pb_text_align_center .et_pb_menu__menu>nav>ul,
        .et_pb_menu--style-left_aligned.et_pb_text_align_center .et_pb_menu__wrap {
            -ms-flex-pack: center;
            justify-content: center
        }

        .et_pb_menu--style-left_aligned.et_pb_text_align_right .et_pb_menu__menu>nav>ul,
        .et_pb_menu--style-left_aligned.et_pb_text_align_right .et_pb_menu__wrap {
            -ms-flex-pack: end;
            justify-content: flex-end
        }

        .et_pb_menu--style-left_aligned.et_pb_text_align_justified .et_pb_menu__menu,
        .et_pb_menu--style-left_aligned.et_pb_text_align_justified .et_pb_menu__menu>nav,
        .et_pb_menu--style-left_aligned.et_pb_text_align_justified .et_pb_menu__wrap {
            -ms-flex-positive: 1;
            flex-grow: 1
        }

        .et_pb_menu--style-left_aligned.et_pb_text_align_justified .et_pb_menu__menu>nav>ul {
            -ms-flex-positive: 1;
            flex-grow: 1;
            -ms-flex-pack: justify;
            justify-content: space-between
        }

        .et_pb_menu--style-centered .et_pb_menu__logo-wrap {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-direction: column;
            flex-direction: column;
            -ms-flex-align: center;
            align-items: center
        }

        .et_pb_menu--style-centered .et_pb_menu__logo-wrap,
        .et_pb_menu--style-centered .et_pb_menu__logo img {
            margin: 0 auto
        }

        .et_pb_menu--style-centered .et_pb_menu__menu>nav>ul,
        .et_pb_menu--style-centered .et_pb_menu__wrap {
            -ms-flex-pack: center;
            justify-content: center
        }

        .et_pb_menu--style-inline_centered_logo .et_pb_menu_inner_container>.et_pb_menu__logo-wrap,
        .et_pb_menu--style-inline_centered_logo .et_pb_row>.et_pb_menu__logo-wrap {
            display: none;
            margin-bottom: 30px
        }

        .et_pb_menu--style-inline_centered_logo .et_pb_menu__logo {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-align: center;
            align-items: center
        }

        .et_pb_menu--style-inline_centered_logo .et_pb_menu__logo,
        .et_pb_menu--style-inline_centered_logo .et_pb_menu__logo img {
            margin: 0 auto
        }

        .et_pb_menu--style-inline_centered_logo .et_pb_menu__wrap {
            -ms-flex-pack: center;
            justify-content: center
        }

        .et_pb_menu--style-inline_centered_logo .et_pb_menu__logo-slot {
            -ms-flex-align: center !important;
            align-items: center !important
        }

        .et_pb_menu--style-inline_centered_logo .et_pb_menu__logo-slot .et_pb_menu__logo,
        .et_pb_menu--style-inline_centered_logo .et_pb_menu__logo-slot .et_pb_menu__logo-wrap {
            width: 100%;
            height: 100%
        }

        .et_pb_menu--style-inline_centered_logo .et_pb_menu__logo-slot img {
            max-height: 100%
        }

        .et_pb_menu .et_pb_menu__logo-slot .et-fb-content-placeholder {
            min-width: 96px
        }

        .et_pb_menu .et_pb_menu__search-container {
            position: absolute;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-pack: stretch;
            justify-content: stretch;
            -ms-flex-line-pack: stretch;
            align-content: stretch;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            z-index: 999
        }

        .et_pb_menu .et_pb_menu__search-container--visible {
            opacity: 1;
            animation: fadeInTop 1s 1 cubic-bezier(.77, 0, .175, 1)
        }

        .et_pb_menu .et_pb_menu__search-container--hidden {
            animation: fadeOutTop 1s 1 cubic-bezier(.77, 0, .175, 1)
        }

        .et_pb_menu .et_pb_menu__search-container--disabled {
            display: none
        }

        .et_pb_menu .et_pb_menu__search {
            -ms-flex: 1 1 auto;
            flex: 1 1 auto;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-pack: stretch;
            justify-content: stretch;
            -ms-flex-align: center;
            align-items: center
        }

        .et_pb_menu .et_pb_menu__search-form {
            -ms-flex: 1 1 auto;
            flex: 1 1 auto
        }

        .et_pb_menu .et_pb_menu__search-input {
            border: 0;
            width: 100%;
            color: #333;
            background: transparent
        }

        .et_pb_menu .et_pb_menu__close-search-button {
            -ms-flex: 0 0 auto;
            flex: 0 0 auto
        }

        .et_pb_menu .et_pb_menu__close-search-button:after {
            content: "M";
            font-size: 1.7em
        }

        @media (min-width:981px) {
            .et_dropdown_animation_fade.et_pb_menu ul li:hover>ul {
                transition: all .2s ease-in-out
            }

            .et_dropdown_animation_slide.et_pb_menu ul li:hover>ul {
                animation: fadeLeft .4s ease-in-out
            }

            .et_dropdown_animation_expand.et_pb_menu ul li:hover>ul {
                -webkit-transform-origin: 0 0;
                animation: Grow .4s ease-in-out;
                -webkit-backface-visibility: visible !important;
                backface-visibility: visible !important
            }

            .et_dropdown_animation_flip.et_pb_menu ul li ul li:hover>ul {
                animation: flipInX .6s ease-in-out;
                -webkit-backface-visibility: visible !important;
                backface-visibility: visible !important
            }

            .et_dropdown_animation_flip.et_pb_menu ul li:hover>ul {
                animation: flipInY .6s ease-in-out;
                -webkit-backface-visibility: visible !important;
                backface-visibility: visible !important
            }

            .et_pb_menu.et_pb_menu_fullwidth .et_pb_row {
                width: 100%;
                max-width: 100%;
                padding: 0 30px !important
            }
        }

        @media (max-width:980px) {

            .et_pb_menu--style-left_aligned .et_pb_menu_inner_container,
            .et_pb_menu--style-left_aligned .et_pb_row {
                -ms-flex-align: center;
                align-items: center
            }

            .et_pb_menu--style-left_aligned .et_pb_menu__wrap {
                -ms-flex-pack: end;
                justify-content: flex-end
            }

            .et_pb_menu--style-left_aligned.et_pb_text_align_center .et_pb_menu__wrap {
                -ms-flex-pack: center;
                justify-content: center
            }

            .et_pb_menu--style-left_aligned.et_pb_text_align_right .et_pb_menu__wrap {
                -ms-flex-pack: end;
                justify-content: flex-end
            }

            .et_pb_menu--style-left_aligned.et_pb_text_align_justified .et_pb_menu__wrap {
                -ms-flex-pack: justify;
                justify-content: space-between
            }

            .et_pb_menu--style-inline_centered_logo .et_pb_menu_inner_container>.et_pb_menu__logo-wrap,
            .et_pb_menu--style-inline_centered_logo .et_pb_row>.et_pb_menu__logo-wrap {
                display: -ms-flexbox;
                display: flex;
                -ms-flex-direction: column;
                flex-direction: column;
                -ms-flex-align: center;
                align-items: center
            }

            .et_pb_menu--style-inline_centered_logo .et_pb_menu_inner_container>.et_pb_menu__logo,
            .et_pb_menu--style-inline_centered_logo .et_pb_row>.et_pb_menu__logo {
                margin: 0 auto
            }

            .et_pb_menu--style-inline_centered_logo .et_pb_menu__logo-slot {
                display: none
            }

            .et_pb_menu .et_pb_row {
                min-height: 81px
            }

            .et_pb_menu .et_pb_menu__menu {
                display: none
            }

            .et_pb_menu .et_mobile_nav_menu {
                float: none;
                margin: 0 6px;
                display: -ms-flexbox;
                display: flex;
                -ms-flex-align: center;
                align-items: center
            }

            .et_pb_menu .et_mobile_menu {
                top: 100%;
                padding: 5%
            }

            .et_pb_menu .et_mobile_menu,
            .et_pb_menu .et_mobile_menu ul {
                list-style: none !important;
                text-align: left
            }

            .et_pb_menu .et_mobile_menu ul {
                padding: 0
            }

            .et_pb_menu .et_pb_mobile_menu_upwards .et_mobile_menu {
                top: auto;
                bottom: 100%
            }
        }

        @keyframes fadeOutTop {
            0% {
                opacity: 1;
                transform: translatey(0)
            }

            to {
                opacity: 0;
                transform: translatey(-60%)
            }
        }

        @keyframes fadeInTop {
            0% {
                opacity: 0;
                transform: translatey(-60%)
            }

            to {
                opacity: 1;
                transform: translatey(0)
            }
        }

        @keyframes fadeInBottom {
            0% {
                opacity: 0;
                transform: translatey(60%)
            }

            to {
                opacity: 1;
                transform: translatey(0)
            }
        }

        @keyframes fadeOutBottom {
            0% {
                opacity: 1;
                transform: translatey(0)
            }

            to {
                opacity: 0;
                transform: translatey(60%)
            }
        }

        @keyframes Grow {
            0% {
                opacity: 0;
                transform: scaleY(.5)
            }

            to {
                opacity: 1;
                transform: scale(1)
            }
        }

        /*!
      * Animate.css - http://daneden.me/animate
      * Licensed under the MIT license - http://opensource.org/licenses/MIT
      * Copyright (c) 2015 Daniel Eden
     */
        @keyframes flipInX {
            0% {
                transform: perspective(400px) rotateX(90deg);
                animation-timing-function: ease-in;
                opacity: 0
            }

            40% {
                transform: perspective(400px) rotateX(-20deg);
                animation-timing-function: ease-in
            }

            60% {
                transform: perspective(400px) rotateX(10deg);
                opacity: 1
            }

            80% {
                transform: perspective(400px) rotateX(-5deg)
            }

            to {
                transform: perspective(400px)
            }
        }

        @keyframes flipInY {
            0% {
                transform: perspective(400px) rotateY(90deg);
                animation-timing-function: ease-in;
                opacity: 0
            }

            40% {
                transform: perspective(400px) rotateY(-20deg);
                animation-timing-function: ease-in
            }

            60% {
                transform: perspective(400px) rotateY(10deg);
                opacity: 1
            }

            80% {
                transform: perspective(400px) rotateY(-5deg)
            }

            to {
                transform: perspective(400px)
            }
        }

        #main-header {
            line-height: 23px;
            font-weight: 500;
            top: 0;
            background-color: #fff;
            width: 100%;
            box-shadow: 0 1px 0 rgba(0, 0, 0, .1);
            position: relative;
            z-index: 99999
        }

        .nav li li {
            padding: 0 20px;
            margin: 0
        }

        .et-menu li li a {
            padding: 6px 20px;
            width: 200px
        }

        .nav li {
            position: relative;
            line-height: 1em
        }

        .nav li li {
            position: relative;
            line-height: 2em
        }

        .nav li ul {
            position: absolute;
            padding: 20px 0;
            z-index: 9999;
            width: 240px;
            background: #fff;
            visibility: hidden;
            opacity: 0;
            border-top: 3px solid #2ea3f2;
            box-shadow: 0 2px 5px rgba(0, 0, 0, .1);
            -moz-box-shadow: 0 2px 5px rgba(0, 0, 0, .1);
            -webkit-box-shadow: 0 2px 5px rgba(0, 0, 0, .1);
            -webkit-transform: translateZ(0);
            text-align: left
        }

        .nav li.et-hover>ul {
            visibility: visible
        }

        .nav li.et-touch-hover>ul,
        .nav li:hover>ul {
            opacity: 1;
            visibility: visible
        }

        .nav li li ul {
            z-index: 1000;
            top: -23px;
            left: 240px
        }

        .nav li.et-reverse-direction-nav li ul {
            left: auto;
            right: 240px
        }

        .nav li:hover {
            visibility: inherit
        }

        .et_mobile_menu li a,
        .nav li li a {
            font-size: 14px;
            transition: all .2s ease-in-out
        }

        .et_mobile_menu li a:hover,
        .nav ul li a:hover {
            background-color: rgba(0, 0, 0, .03);
            opacity: .7
        }

        .et-dropdown-removing>ul {
            display: none
        }

        .mega-menu .et-dropdown-removing>ul {
            display: block
        }

        .et-menu .menu-item-has-children>a:first-child:after {
            font-family: ETmodules;
            content: "3";
            font-size: 16px;
            position: absolute;
            right: 0;
            top: 0;
            font-weight: 800
        }

        .et-menu .menu-item-has-children>a:first-child {
            padding-right: 20px
        }

        .et-menu li li.menu-item-has-children>a:first-child:after {
            right: 20px;
            top: 6px
        }

        .et-menu-nav li.mega-menu {
            position: inherit
        }

        .et-menu-nav li.mega-menu>ul {
            padding: 30px 20px;
            position: absolute !important;
            width: 100%;
            left: 0 !important
        }

        .et-menu-nav li.mega-menu ul li {
            margin: 0;
            float: left !important;
            display: block !important;
            padding: 0 !important
        }

        .et-menu-nav li.mega-menu li>ul {
            animation: none !important;
            padding: 0;
            border: none;
            left: auto;
            top: auto;
            width: 240px !important;
            position: relative;
            box-shadow: none;
            -webkit-box-shadow: none
        }

        .et-menu-nav li.mega-menu li ul {
            visibility: visible;
            opacity: 1;
            display: none
        }

        .et-menu-nav li.mega-menu.et-hover li ul,
        .et-menu-nav li.mega-menu:hover li ul {
            display: block
        }

        .et-menu-nav li.mega-menu:hover>ul {
            opacity: 1 !important;
            visibility: visible !important
        }

        .et-menu-nav li.mega-menu>ul>li>a:first-child {
            padding-top: 0 !important;
            font-weight: 700;
            border-bottom: 1px solid rgba(0, 0, 0, .03)
        }

        .et-menu-nav li.mega-menu>ul>li>a:first-child:hover {
            background-color: transparent !important
        }

        .et-menu-nav li.mega-menu li>a {
            width: 200px !important
        }

        .et-menu-nav li.mega-menu.mega-menu-parent li>a,
        .et-menu-nav li.mega-menu.mega-menu-parent li li {
            width: 100% !important
        }

        .et-menu-nav li.mega-menu.mega-menu-parent li>.sub-menu {
            float: left;
            width: 100% !important
        }

        .et-menu-nav li.mega-menu>ul>li {
            width: 25%;
            margin: 0
        }

        .et-menu-nav li.mega-menu.mega-menu-parent-3>ul>li {
            width: 33.33%
        }

        .et-menu-nav li.mega-menu.mega-menu-parent-2>ul>li {
            width: 50%
        }

        .et-menu-nav li.mega-menu.mega-menu-parent-1>ul>li {
            width: 100%
        }

        .et_pb_fullwidth_menu li.mega-menu .menu-item-has-children>a:first-child:after,
        .et_pb_menu li.mega-menu .menu-item-has-children>a:first-child:after {
            display: none
        }

        .et_fullwidth_nav #top-menu li.mega-menu>ul {
            width: auto;
            left: 30px !important;
            right: 30px !important
        }

        .et_mobile_menu {
            position: absolute;
            left: 0;
            padding: 5%;
            background: #fff;
            width: 100%;
            visibility: visible;
            opacity: 1;
            display: none;
            z-index: 9999;
            border-top: 3px solid #2ea3f2;
            box-shadow: 0 2px 5px rgba(0, 0, 0, .1);
            -moz-box-shadow: 0 2px 5px rgba(0, 0, 0, .1);
            -webkit-box-shadow: 0 2px 5px rgba(0, 0, 0, .1)
        }

        #main-header .et_mobile_menu li ul,
        .et_pb_fullwidth_menu .et_mobile_menu li ul,
        .et_pb_menu .et_mobile_menu li ul {
            visibility: visible !important;
            display: block !important;
            padding-left: 10px
        }

        .et_mobile_menu li li {
            padding-left: 5%
        }

        .et_mobile_menu li a {
            border-bottom: 1px solid rgba(0, 0, 0, .03);
            color: #666;
            padding: 10px 5%;
            display: block
        }

        .et_mobile_menu .menu-item-has-children>a {
            font-weight: 700;
            background-color: rgba(0, 0, 0, .03)
        }

        .et_mobile_menu li .menu-item-has-children>a {
            background-color: transparent
        }

        .et_mobile_nav_menu {
            float: right;
            display: none
        }

        .mobile_menu_bar {
            position: relative;
            display: block;
            line-height: 0
        }

        .mobile_menu_bar:before {
            content: "a";
            font-size: 32px;
            position: relative;
            left: 0;
            top: 0;
            cursor: pointer
        }

        .et_pb_module .mobile_menu_bar:before {
            top: 2px
        }

        .mobile_nav .select_page {
            display: none
        }

        .et_pb_slider .et_pb_container {
            width: 100%;
            margin: 0 auto;
            position: relative;
            z-index: 2
        }

        .et_pb_with_border .et_pb_slide_image img {
            border: 0 solid #333
        }

        .et_pb_slides .et_pb_container {
            display: table;
            table-layout: fixed
        }

        .et_pb_slides .et_pb_slider_container_inner {
            display: table-cell;
            width: 100%;
            vertical-align: middle
        }

        .et_pb_slides .et_pb_empty_slide.et_pb_container,
        .et_pb_slides .et_pb_empty_slide .et_pb_slider_container_inner {
            display: block
        }

        .et_pb_slide_content {
            font-size: 14px;
            font-weight: 400;
            color: #333
        }

        .et_pb_slide_content p:last-of-type {
            padding-bottom: 0
        }

        .et_pb_bg_layout_dark .et_pb_slide_content {
            color: #fff
        }

        .et_pb_slider_with_text_overlay .et_pb_text_overlay_wrapper {
            padding: 30px;
            border-radius: 3px
        }

        .et_pb_slide_description {
            word-wrap: break-word
        }

        .et-pb-active-slide .et_pb_slide_description {
            animation-duration: .7s;
            animation-delay: .9s;
            animation-timing-function: ease-in-out;
            animation-fill-mode: both;
            animation-name: fadeBottom
        }

        .et_pb_bg_layout_dark .et_pb_slide_description {
            text-shadow: 0 1px 3px rgba(0, 0, 0, .3)
        }

        .et_pb_slide_description,
        .et_pb_slider_fullwidth_off .et_pb_slide_description {
            padding: 16% 8%;
            width: auto;
            margin: auto
        }

        .et_pb_slide_with_image .et_pb_slide_description {
            width: 47.25%;
            padding-left: 0;
            padding-right: 0;
            text-align: left;
            float: right
        }

        .et_pb_slide_description .et_pb_slide_title {
            font-weight: 300;
            margin-top: 0;
            font-size: 46px
        }

        .et_pb_slide_image {
            position: absolute;
            bottom: 0
        }

        .et_pb_slide_video {
            position: absolute;
            top: 50%;
            bottom: auto
        }

        .et_pb_slide:first-child .et_pb_slide_image img {
            opacity: 0
        }

        .et_pb_slide:first-child .et_pb_slide_image img.active {
            opacity: 1;
            animation-duration: .7s;
            animation-delay: .6s;
            animation-timing-function: ease-in-out;
            animation-fill-mode: both;
            animation-name: fadeLeft
        }

        .et_pb_media_alignment_center .et_pb_slide_image {
            bottom: auto;
            top: 50%
        }

        .et-pb-active-slide .et_pb_slide_image,
        .et-pb-active-slide .et_pb_slide_video {
            animation-duration: .7s;
            animation-delay: .6s;
            animation-timing-function: ease-in-out;
            animation-fill-mode: both
        }

        .et-pb-active-slide .et_pb_slide_image {
            animation-name: fadeLeft
        }

        .et_pb_slide_image img {
            width: auto;
            vertical-align: bottom
        }

        .et_pb_slide_image,
        .et_pb_slide_video {
            width: 47.25%;
            margin-right: 5.5%
        }

        .et_pb_slide_video .mejs-mediaelement {
            position: relative
        }

        .et_pb_slide_video .mejs-mediaelement .wp-video-shortcode {
            display: block
        }

        .et_pb_slide_video .wp-video-shortcode video,
        .et_pb_slide_video video.wp-video-shortcode {
            height: auto
        }

        .et_pb_slider_with_overlay .et_pb_slide_overlay_container {
            position: absolute;
            width: 100%;
            height: 100%;
            left: 0;
            top: 0;
            z-index: 1
        }

        .et_pb_bg_layout_light.et_pb_slider_with_overlay .et_pb_slide_overlay_container,
        .et_pb_bg_layout_light.et_pb_slider_with_text_overlay .et_pb_text_overlay_wrapper {
            background-color: hsla(0, 0%, 100%, .9)
        }

        .et_pb_bg_layout_dark.et_pb_slider_with_overlay .et_pb_slide_overlay_container,
        .et_pb_bg_layout_dark.et_pb_slider_with_text_overlay .et_pb_text_overlay_wrapper {
            background-color: rgba(0, 0, 0, .3)
        }

        .et_pb_column_1_2 .et_pb_slider_fullwidth_off .et_pb_slide_description,
        .et_pb_column_1_3 .et_pb_slider_fullwidth_off .et_pb_slide_description,
        .et_pb_column_2_5 .et_pb_slider_fullwidth_off .et_pb_slide_description,
        .et_pb_column_3_5 .et_pb_slider_fullwidth_off .et_pb_slide_description,
        .et_pb_column_3_8 .et_pb_slider_fullwidth_off .et_pb_slide_description {
            text-align: center
        }

        .et_pb_column_1_4 .et_pb_slider_fullwidth_off .et_pb_slide_content,
        .et_pb_column_1_4 .et_pb_slider_fullwidth_off .et_pb_slide_image,
        .et_pb_column_1_4 .et_pb_slider_fullwidth_off .et_pb_slide_video,
        .et_pb_column_1_5 .et_pb_slider_fullwidth_off .et_pb_slide_content,
        .et_pb_column_1_5 .et_pb_slider_fullwidth_off .et_pb_slide_image,
        .et_pb_column_1_5 .et_pb_slider_fullwidth_off .et_pb_slide_video,
        .et_pb_column_1_6 .et_pb_slider_fullwidth_off .et_pb_slide_content,
        .et_pb_column_1_6 .et_pb_slider_fullwidth_off .et_pb_slide_image,
        .et_pb_column_1_6 .et_pb_slider_fullwidth_off .et_pb_slide_video {
            display: none
        }

        .et_pb_column_1_4 .et_pb_slider_fullwidth_off .et_pb_slide_description,
        .et_pb_column_1_4 .et_pb_slider_fullwidth_off .et_pb_slide_with_image .et_pb_slide_description,
        .et_pb_column_1_5 .et_pb_slider_fullwidth_off .et_pb_slide_description,
        .et_pb_column_1_5 .et_pb_slider_fullwidth_off .et_pb_slide_with_image .et_pb_slide_description,
        .et_pb_column_1_6 .et_pb_slider_fullwidth_off .et_pb_slide_description,
        .et_pb_column_1_6 .et_pb_slider_fullwidth_off .et_pb_slide_with_image .et_pb_slide_description {
            text-align: center
        }

        .et_pb_column_1_2 .et_pb_slider_fullwidth_off .et_pb_slide_image,
        .et_pb_column_1_2 .et_pb_slider_fullwidth_off .et_pb_slide_video,
        .et_pb_column_1_3 .et_pb_slider_fullwidth_off .et_pb_slide_image,
        .et_pb_column_1_3 .et_pb_slider_fullwidth_off .et_pb_slide_video,
        .et_pb_column_1_3 .et_pb_slider_fullwidth_off .et_pb_slide_with_image .et_pb_slide_content,
        .et_pb_column_2_5 .et_pb_slider_fullwidth_off .et_pb_slide_image,
        .et_pb_column_2_5 .et_pb_slider_fullwidth_off .et_pb_slide_video,
        .et_pb_column_2_5 .et_pb_slider_fullwidth_off .et_pb_slide_with_image .et_pb_slide_content,
        .et_pb_column_3_5 .et_pb_slider_fullwidth_off .et_pb_slide_image,
        .et_pb_column_3_5 .et_pb_slider_fullwidth_off .et_pb_slide_video,
        .et_pb_column_3_8 .et_pb_slider_fullwidth_off .et_pb_slide_image,
        .et_pb_column_3_8 .et_pb_slider_fullwidth_off .et_pb_slide_video,
        .et_pb_column_3_8 .et_pb_slider_fullwidth_off .et_pb_slide_with_image .et_pb_slide_content {
            display: none
        }

        .et_pb_column_1_2 .et_pb_slide_content,
        .et_pb_column_1_3 .et_pb_slide_content,
        .et_pb_column_1_4 .et_pb_slide_content,
        .et_pb_column_1_5 .et_pb_slide_content,
        .et_pb_column_1_6 .et_pb_slide_content,
        .et_pb_column_2_5 .et_pb_slide_content,
        .et_pb_column_3_4 .et_pb_column_3_8 .et_pb_slide_content,
        .et_pb_column_3_5 .et_pb_slide_content {
            font-size: 14px
        }

        .et_pb_column .et_pb_slider_fullwidth_off .et_pb_slide_content,
        .et_pb_column .et_pb_slider_fullwidth_off .et_pb_slide_image,
        .et_pb_column .et_pb_slider_fullwidth_off .et_pb_slide_video,
        .et_pb_column .et_pb_slider_fullwidth_off .et_pb_slide_with_image .et_pb_slide_content {
            display: block
        }

        .et_pb_column_1_2 .et_pb_slide_description h2.et_pb_slide_title,
        .et_pb_column_3_4 .et_pb_column_3_8 .et_pb_slide_description h2.et_pb_slide_title,
        .et_pb_column_3_5 .et_pb_slide_description h2.et_pb_slide_title {
            font-size: 26px;
            font-weight: 400
        }

        .et_pb_column_1_3 .et_pb_slide_description h2.et_pb_slide_title,
        .et_pb_column_1_4 .et_pb_slide_description h2.et_pb_slide_title,
        .et_pb_column_1_5 .et_pb_slide_description h2.et_pb_slide_title,
        .et_pb_column_1_6 .et_pb_slide_description h2.et_pb_slide_title,
        .et_pb_column_2_5 .et_pb_slide_description h2.et_pb_slide_title {
            font-size: 22px;
            font-weight: 400
        }

        @media (min-width:981px) and (max-width:1100px) {

            .et_pb_column_1_2 .et_pb_slide_content,
            .et_pb_column_1_3 .et_pb_slide_content,
            .et_pb_column_1_4 .et_pb_slide_content,
            .et_pb_column_1_5 .et_pb_slide_content,
            .et_pb_column_1_6 .et_pb_slide_content,
            .et_pb_column_2_3 .et_pb_slide_content,
            .et_pb_column_2_5 .et_pb_slide_content,
            .et_pb_column_3_5 .et_pb_slide_content {
                font-size: 14px
            }

            .et_pb_column_1_2 .et_pb_slide_description h2.et_pb_slide_title,
            .et_pb_column_1_3 .et_pb_slide_description h2.et_pb_slide_title,
            .et_pb_column_1_4 .et_pb_slide_description h2.et_pb_slide_title,
            .et_pb_column_1_5 .et_pb_slide_description h2.et_pb_slide_title,
            .et_pb_column_1_6 .et_pb_slide_description h2.et_pb_slide_title,
            .et_pb_column_2_3 .et_pb_slide_description h2.et_pb_slide_title,
            .et_pb_column_2_5 .et_pb_slide_description h2.et_pb_slide_title,
            .et_pb_column_3_5 .et_pb_slide_description h2.et_pb_slide_title {
                font-size: 18px
            }
        }

        @media (min-width:981px) {

            .et_pb_column_1_2 .et_pb_slide_image,
            .et_pb_column_1_2 .et_pb_slide_video,
            .et_pb_column_1_3 .et_pb_slide_image,
            .et_pb_column_1_3 .et_pb_slide_video,
            .et_pb_column_1_4 .et_pb_slide_image,
            .et_pb_column_1_4 .et_pb_slide_video,
            .et_pb_column_1_5 .et_pb_slide_image,
            .et_pb_column_1_5 .et_pb_slide_video,
            .et_pb_column_1_6 .et_pb_slide_image,
            .et_pb_column_1_6 .et_pb_slide_video,
            .et_pb_column_2_5 .et_pb_slide_image,
            .et_pb_column_2_5 .et_pb_slide_video,
            .et_pb_column_3_5 .et_pb_slide_image,
            .et_pb_column_3_5 .et_pb_slide_video,
            .et_pb_column_3_8 .et_pb_slide_image,
            .et_pb_column_3_8 .et_pb_slide_video {
                top: auto;
                bottom: auto;
                position: relative
            }

            .et_pb_column_1_2 .et_pb_slide_image,
            .et_pb_column_1_2 .et_pb_slide_video,
            .et_pb_column_1_2 .et_pb_slide_with_image .et_pb_slide_description,
            .et_pb_column_1_3 .et_pb_slide_image,
            .et_pb_column_1_3 .et_pb_slide_video,
            .et_pb_column_1_3 .et_pb_slide_with_image .et_pb_slide_description,
            .et_pb_column_1_4 .et_pb_slide_image,
            .et_pb_column_1_4 .et_pb_slide_video,
            .et_pb_column_1_4 .et_pb_slide_with_image .et_pb_slide_description,
            .et_pb_column_1_5 .et_pb_slide_image,
            .et_pb_column_1_5 .et_pb_slide_video,
            .et_pb_column_1_5 .et_pb_slide_with_image .et_pb_slide_description,
            .et_pb_column_1_6 .et_pb_slide_image,
            .et_pb_column_1_6 .et_pb_slide_video,
            .et_pb_column_1_6 .et_pb_slide_with_image .et_pb_slide_description,
            .et_pb_column_2_5 .et_pb_slide_image,
            .et_pb_column_2_5 .et_pb_slide_video,
            .et_pb_column_2_5 .et_pb_slide_with_image .et_pb_slide_description,
            .et_pb_column_3_5 .et_pb_slide_image,
            .et_pb_column_3_5 .et_pb_slide_video,
            .et_pb_column_3_5 .et_pb_slide_with_image .et_pb_slide_description,
            .et_pb_column_3_8 .et_pb_slide_image,
            .et_pb_column_3_8 .et_pb_slide_video,
            .et_pb_column_3_8 .et_pb_slide_with_image .et_pb_slide_description {
                width: 100% !important
            }

            .et_pb_column_1_2 .et_pb_slide_image,
            .et_pb_column_1_2 .et_pb_slide_video,
            .et_pb_column_1_3 .et_pb_slide_image,
            .et_pb_column_1_3 .et_pb_slide_video,
            .et_pb_column_1_4 .et_pb_slide_image,
            .et_pb_column_1_4 .et_pb_slide_video,
            .et_pb_column_1_5 .et_pb_slide_image,
            .et_pb_column_1_5 .et_pb_slide_video,
            .et_pb_column_1_6 .et_pb_slide_image,
            .et_pb_column_1_6 .et_pb_slide_video,
            .et_pb_column_2_5 .et_pb_slide_image,
            .et_pb_column_2_5 .et_pb_slide_video,
            .et_pb_column_3_5 .et_pb_slide_image,
            .et_pb_column_3_5 .et_pb_slide_video,
            .et_pb_column_3_8 .et_pb_slide_image,
            .et_pb_column_3_8 .et_pb_slide_video {
                padding: 0;
                margin: 10% 0 0 !important
            }

            .et_pb_column_1_3 .et_pb_slide_description,
            .et_pb_column_2_5 .et_pb_slide_description {
                padding-bottom: 26%
            }

            .et_pb_column_1_4 .et_pb_slide .et_pb_slide_description,
            .et_pb_column_1_5 .et_pb_slide .et_pb_slide_description,
            .et_pb_column_1_6 .et_pb_slide .et_pb_slide_description {
                padding-bottom: 34%
            }
        }

        @media (max-width:980px) {

            .et_pb_column_1_4 .et_pb_slider_fullwidth_off h2,
            .et_pb_column_1_5 .et_pb_slider_fullwidth_off h2,
            .et_pb_column_1_6 .et_pb_slider_fullwidth_off h2,
            .et_pb_slide_description h2.et_pb_slide_title {
                font-size: 26px;
                font-weight: 500
            }

            .et_pb_slide_description .et_pb_slide_title {
                font-weight: 500
            }

            .et_pb_slide_content {
                font-size: 13px;
                font-weight: 400
            }

            .et_pb_slide_description {
                text-align: center
            }

            .et_pb_slide_with_image .et_pb_slide_description {
                text-align: left
            }

            .et_pb_column_1_4 .et_pb_slider_fullwidth_off .et_pb_container,
            .et_pb_column_1_5 .et_pb_slider_fullwidth_off .et_pb_container,
            .et_pb_column_1_6 .et_pb_slider_fullwidth_off .et_pb_container {
                min-height: auto
            }

            .et_pb_column_1_4 .et_pb_slider_fullwidth_off .et_pb_slide_content,
            .et_pb_column_1_5 .et_pb_slider_fullwidth_off .et_pb_slide_content,
            .et_pb_column_1_6 .et_pb_slider_fullwidth_off .et_pb_slide_content {
                display: block
            }

            .et_pb_bg_layout_light_tablet.et_pb_slider_with_overlay .et_pb_slide_overlay_container,
            .et_pb_bg_layout_light_tablet.et_pb_slider_with_text_overlay .et_pb_text_overlay_wrapper {
                background-color: hsla(0, 0%, 100%, .9)
            }

            .et_pb_bg_layout_dark_tablet.et_pb_slider_with_overlay .et_pb_slide_overlay_container,
            .et_pb_bg_layout_dark_tablet.et_pb_slider_with_text_overlay .et_pb_text_overlay_wrapper {
                background-color: rgba(0, 0, 0, .3)
            }
        }

        @media (max-width:767px) {
            .et_pb_slide_content {
                font-size: 12px
            }

            .et_pb_slider.et_pb_module .et_pb_slides .et_pb_slide_content {
                font-size: 14px
            }

            .et_pb_slide_description h2.et_pb_slide_title {
                font-size: 24px
            }

            .et_pb_slider.et_pb_module .et_pb_slides .et_pb_slide_description h2.et_pb_slide_title {
                font-size: 20px
            }

            .et_pb_slide_description,
            .et_pb_slide_with_image .et_pb_slide_description,
            .et_pb_slider_fullwidth_off .et_pb_slide_description {
                text-align: center
            }

            .et_pb_slide_with_image .et_pb_slide_description {
                float: none;
                text-align: center;
                width: 100%
            }

            .et_pb_media_alignment_center .et_pb_slide_image {
                top: 0
            }

            .et_pb_slide_image,
            .et_pb_slide_video,
            .et_pb_slide_with_image .et_pb_slide_description {
                width: 100%
            }

            .et_pb_slider_show_image .et_pb_slide_image,
            .et_pb_slider_show_image .et_pb_slide_video {
                display: block !important
            }

            .et_pb_slide_image,
            .et_pb_slide_video {
                display: none !important;
                position: relative;
                top: auto;
                margin: 50px auto 0;
                padding: 0;
                margin-top: 6% !important
            }

            .et_pb_slide_video {
                float: none
            }

            .et_pb_slide_image img {
                max-height: 300px
            }

            .et_pb_section_first .et_pb_slide_image img {
                max-height: 300px !important
            }

            .et_pb_section_first .et_pb_slide_image {
                margin: 0 !important;
                top: 20px
            }

            .et_pb_slider_with_overlay .et_pb_slide_image,
            .et_pb_slider_with_overlay .et_pb_slide_video,
            .et_pb_slider_with_overlay .et_pb_slide_with_image .et_pb_slide_description {
                width: 100%
            }

            .et_pb_slider_with_overlay .et_pb_slide_image {
                margin-top: 0 !important;
                padding-top: 6%
            }

            .et_pb_bg_layout_light_phone .et_pb_slide_content {
                color: #333
            }

            .et_pb_bg_layout_dark_phone .et_pb_slide_description {
                text-shadow: 0 1px 3px rgba(0, 0, 0, .3)
            }

            .et_pb_bg_layout_dark_phone .et_pb_slide_content {
                color: #fff
            }
        }

        @media (min-width:480px) {

            .et_pb_column_1_4 .et_pb_slide_description,
            .et_pb_column_1_5 .et_pb_slide_description,
            .et_pb_column_1_6 .et_pb_slide_description {
                padding-bottom: 26%
            }
        }

        @media (max-width:479px) {
            .et_pb_slide_description h2.et_pb_slide_title {
                font-size: 20px
            }

            .et_pb_slide_content {
                font-weight: 400;
                font-size: 10px;
                display: block
            }

            .et_pb_slider_fullwidth_off .et_pb_more_button,
            .et_pb_slider_fullwidth_off .et_pb_slide_content {
                display: none
            }
        }

        .et_pb_slider {
            position: relative;
            overflow: hidden
        }

        .et_pb_slide {
            padding: 0 6%;
            background-size: cover;
            background-position: 50%;
            background-repeat: no-repeat
        }

        .et_pb_slider .et_pb_slide {
            display: none;
            float: left;
            margin-right: -100%;
            position: relative;
            width: 100%;
            text-align: center;
            list-style: none !important;
            background-position: 50%;
            background-size: 100%;
            background-size: cover
        }

        .et_pb_slider .et_pb_slide:first-child {
            display: list-item
        }

        .et-pb-controllers {
            position: absolute;
            bottom: 20px;
            left: 0;
            width: 100%;
            text-align: center;
            z-index: 10
        }

        .et-pb-controllers a {
            display: inline-block;
            background-color: hsla(0, 0%, 100%, .5);
            text-indent: -9999px;
            border-radius: 7px;
            width: 7px;
            height: 7px;
            margin-right: 10px;
            padding: 0;
            opacity: .5
        }

        .et-pb-controllers .et-pb-active-control {
            opacity: 1
        }

        .et-pb-controllers a:last-child {
            margin-right: 0
        }

        .et-pb-controllers .et-pb-active-control {
            background-color: #fff
        }

        .et_pb_slides .et_pb_temp_slide {
            display: block
        }

        .et_pb_slides:after {
            content: "";
            display: block;
            clear: both;
            visibility: hidden;
            line-height: 0;
            height: 0;
            width: 0
        }

        @media (max-width:980px) {
            .et_pb_bg_layout_light_tablet .et-pb-controllers .et-pb-active-control {
                background-color: #333
            }

            .et_pb_bg_layout_light_tablet .et-pb-controllers a {
                background-color: rgba(0, 0, 0, .3)
            }

            .et_pb_bg_layout_light_tablet .et_pb_slide_content {
                color: #333
            }

            .et_pb_bg_layout_dark_tablet .et_pb_slide_description {
                text-shadow: 0 1px 3px rgba(0, 0, 0, .3)
            }

            .et_pb_bg_layout_dark_tablet .et_pb_slide_content {
                color: #fff
            }

            .et_pb_bg_layout_dark_tablet .et-pb-controllers .et-pb-active-control {
                background-color: #fff
            }

            .et_pb_bg_layout_dark_tablet .et-pb-controllers a {
                background-color: hsla(0, 0%, 100%, .5)
            }
        }

        @media (max-width:767px) {
            .et-pb-controllers {
                position: absolute;
                bottom: 5%;
                left: 0;
                width: 100%;
                text-align: center;
                z-index: 10;
                height: 14px
            }

            .et_transparent_nav .et_pb_section:first-child .et-pb-controllers {
                bottom: 18px
            }

            .et_pb_bg_layout_light_phone.et_pb_slider_with_overlay .et_pb_slide_overlay_container,
            .et_pb_bg_layout_light_phone.et_pb_slider_with_text_overlay .et_pb_text_overlay_wrapper {
                background-color: hsla(0, 0%, 100%, .9)
            }

            .et_pb_bg_layout_light_phone .et-pb-controllers .et-pb-active-control {
                background-color: #333
            }

            .et_pb_bg_layout_dark_phone.et_pb_slider_with_overlay .et_pb_slide_overlay_container,
            .et_pb_bg_layout_dark_phone.et_pb_slider_with_text_overlay .et_pb_text_overlay_wrapper,
            .et_pb_bg_layout_light_phone .et-pb-controllers a {
                background-color: rgba(0, 0, 0, .3)
            }

            .et_pb_bg_layout_dark_phone .et-pb-controllers .et-pb-active-control {
                background-color: #fff
            }

            .et_pb_bg_layout_dark_phone .et-pb-controllers a {
                background-color: hsla(0, 0%, 100%, .5)
            }
        }

        .et_mobile_device .et_pb_slider_parallax .et_pb_slide,
        .et_mobile_device .et_pb_slides .et_parallax_bg.et_pb_parallax_css {
            background-attachment: scroll
        }

        .et-pb-arrow-next,
        .et-pb-arrow-prev {
            position: absolute;
            top: 50%;
            z-index: 100;
            font-size: 48px;
            color: #fff;
            margin-top: -24px;
            transition: all .2s ease-in-out;
            opacity: 0
        }

        .et_pb_bg_layout_light .et-pb-arrow-next,
        .et_pb_bg_layout_light .et-pb-arrow-prev {
            color: #333
        }

        .et_pb_slider:hover .et-pb-arrow-prev {
            left: 22px;
            opacity: 1
        }

        .et_pb_slider:hover .et-pb-arrow-next {
            right: 22px;
            opacity: 1
        }

        .et_pb_bg_layout_light .et-pb-controllers .et-pb-active-control {
            background-color: #333
        }

        .et_pb_bg_layout_light .et-pb-controllers a {
            background-color: rgba(0, 0, 0, .3)
        }

        .et-pb-arrow-next:hover,
        .et-pb-arrow-prev:hover {
            text-decoration: none
        }

        .et-pb-arrow-next span,
        .et-pb-arrow-prev span {
            display: none
        }

        .et-pb-arrow-prev {
            left: -22px
        }

        .et-pb-arrow-next {
            right: -22px
        }

        .et-pb-arrow-prev:before {
            content: "4"
        }

        .et-pb-arrow-next:before {
            content: "5"
        }

        .format-gallery .et-pb-arrow-next,
        .format-gallery .et-pb-arrow-prev {
            color: #fff
        }

        .et_pb_column_1_3 .et_pb_slider:hover .et-pb-arrow-prev,
        .et_pb_column_1_4 .et_pb_slider:hover .et-pb-arrow-prev,
        .et_pb_column_1_5 .et_pb_slider:hover .et-pb-arrow-prev,
        .et_pb_column_1_6 .et_pb_slider:hover .et-pb-arrow-prev,
        .et_pb_column_2_5 .et_pb_slider:hover .et-pb-arrow-prev {
            left: 0
        }

        .et_pb_column_1_3 .et_pb_slider:hover .et-pb-arrow-next,
        .et_pb_column_1_4 .et_pb_slider:hover .et-pb-arrow-prev,
        .et_pb_column_1_5 .et_pb_slider:hover .et-pb-arrow-prev,
        .et_pb_column_1_6 .et_pb_slider:hover .et-pb-arrow-prev,
        .et_pb_column_2_5 .et_pb_slider:hover .et-pb-arrow-next {
            right: 0
        }

        .et_pb_column_1_4 .et_pb_slider .et_pb_slide,
        .et_pb_column_1_5 .et_pb_slider .et_pb_slide,
        .et_pb_column_1_6 .et_pb_slider .et_pb_slide {
            min-height: 170px
        }

        .et_pb_column_1_4 .et_pb_slider:hover .et-pb-arrow-next,
        .et_pb_column_1_5 .et_pb_slider:hover .et-pb-arrow-next,
        .et_pb_column_1_6 .et_pb_slider:hover .et-pb-arrow-next {
            right: 0
        }

        @media (max-width:980px) {

            .et_pb_bg_layout_light_tablet .et-pb-arrow-next,
            .et_pb_bg_layout_light_tablet .et-pb-arrow-prev {
                color: #333
            }

            .et_pb_bg_layout_dark_tablet .et-pb-arrow-next,
            .et_pb_bg_layout_dark_tablet .et-pb-arrow-prev {
                color: #fff
            }
        }

        @media (max-width:767px) {
            .et_pb_slider:hover .et-pb-arrow-prev {
                left: 0;
                opacity: 1
            }

            .et_pb_slider:hover .et-pb-arrow-next {
                right: 0;
                opacity: 1
            }

            .et_pb_bg_layout_light_phone .et-pb-arrow-next,
            .et_pb_bg_layout_light_phone .et-pb-arrow-prev {
                color: #333
            }

            .et_pb_bg_layout_dark_phone .et-pb-arrow-next,
            .et_pb_bg_layout_dark_phone .et-pb-arrow-prev {
                color: #fff
            }
        }

        .et_mobile_device .et-pb-arrow-prev {
            left: 22px;
            opacity: 1
        }

        .et_mobile_device .et-pb-arrow-next {
            right: 22px;
            opacity: 1
        }

        @media (max-width:767px) {
            .et_mobile_device .et-pb-arrow-prev {
                left: 0;
                opacity: 1
            }

            .et_mobile_device .et-pb-arrow-next {
                right: 0;
                opacity: 1
            }
        }

        .et_pb_button[data-icon]:not([data-icon=""]):after {
            content: attr(data-icon)
        }

        @media (max-width:980px) {
            .et_pb_button[data-icon-tablet]:not([data-icon-tablet=""]):after {
                content: attr(data-icon-tablet)
            }
        }

        @media (max-width:767px) {
            .et_pb_button[data-icon-phone]:not([data-icon-phone=""]):after {
                content: attr(data-icon-phone)
            }
        }

        .et_pb_bg_layout_light .et_pb_promo_button {
            color: #2ea3f2
        }

        .et-promo {
            background-color: #1f6581;
            padding: 40px 0 25px
        }

        .et-promo-description {
            float: left;
            padding: 0 60px;
            word-wrap: break-word;
            width: 754px
        }

        .et-promo-description p {
            color: #fff
        }

        .et-promo-button {
            padding-right: 60px;
            display: inline-block;
            font-weight: 500;
            font-size: 20px;
            color: #fff;
            background-color: rgba(0, 0, 0, .35);
            border-radius: 5px;
            padding: 14px 20px;
            margin-top: 20px;
            float: left
        }

        .et_pb_promo {
            padding: 40px 60px;
            text-align: center
        }

        .et_pb_promo_description {
            padding-bottom: 20px;
            position: relative
        }

        .et_pb_promo_description p:last-of-type {
            padding-bottom: 0
        }

        .et_pb_promo_button {
            display: inline-block;
            color: inherit
        }

        .et_pb_promo_button:hover {
            text-decoration: none
        }

        .et_pb_promo_button:hover:after {
            opacity: 1;
            margin-left: 0
        }

        .et_pb_column_1_2 .et_pb_promo,
        .et_pb_column_1_3 .et_pb_promo,
        .et_pb_column_1_4 .et_pb_promo,
        .et_pb_column_1_5 .et_pb_promo,
        .et_pb_column_1_6 .et_pb_promo,
        .et_pb_column_2_5 .et_pb_promo,
        .et_pb_column_3_5 .et_pb_promo {
            padding: 40px
        }

        .et_pb_has_bg_hover.et_pb_promo:hover {
            padding: 40px 60px !important;
            transition: padding .4s ease-in-out
        }

        .et_pb_column_1_2 .et_pb_has_bg_hover.et_pb_promo:hover,
        .et_pb_column_1_3 .et_pb_has_bg_hover.et_pb_promo:hover,
        .et_pb_column_1_4 .et_pb_has_bg_hover.et_pb_promo:hover,
        .et_pb_column_1_5 .et_pb_has_bg_hover.et_pb_promo:hover,
        .et_pb_column_1_6 .et_pb_has_bg_hover.et_pb_promo:hover,
        .et_pb_column_2_5 .et_pb_has_bg_hover.et_pb_promo:hover,
        .et_pb_column_3_5 .et_pb_has_bg_hover.et_pb_promo:hover {
            padding: 40px !important
        }

        .et_pb_no_bg_hover.et_pb_promo:hover {
            padding: 0 !important
        }

        @media (max-width:980px) {
            .et_pb_has_bg_tablet.et_pb_promo {
                padding: 40px !important
            }

            .et_pb_no_bg_tablet.et_pb_promo {
                padding: 0 !important
            }

            .et_pb_bg_layout_light_tablet .et_pb_promo_button {
                color: #2ea3f2
            }

            .et_pb_bg_layout_dark_tablet .et_pb_promo_button {
                color: inherit
            }
        }

        @media (max-width:767px) {
            .et_pb_promo {
                padding: 40px
            }

            .et_pb_has_bg_phone.et_pb_promo {
                padding: 40px !important
            }

            .et_pb_no_bg_phone.et_pb_promo {
                padding: 0 !important
            }

            .et_pb_bg_layout_light_phone .et_pb_promo_button {
                color: #2ea3f2
            }

            .et_pb_bg_layout_dark_phone .et_pb_promo_button {
                color: inherit
            }
        }

        @media (max-width:479px) {
            .et_pb_promo {
                padding: 40px
            }
        }

        .et_pb_button[data-icon]:not([data-icon=""]):after {
            content: attr(data-icon)
        }

        @media (max-width:980px) {
            .et_pb_button[data-icon-tablet]:not([data-icon-tablet=""]):after {
                content: attr(data-icon-tablet)
            }
        }

        @media (max-width:767px) {
            .et_pb_button[data-icon-phone]:not([data-icon-phone=""]):after {
                content: attr(data-icon-phone)
            }
        }
    </style>




    <!--BEGIN: TRACKING CODE MANAGER BY INTELLYWP.COM IN HEAD//-->
    <!-- Google Tag Manager -->
    {{-- <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                '../www.googletagmanager.com/gtm5445.html?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-57JP8DK');
    </script> --}}
    <!-- End Google Tag Manager -->
    <!--END: https://wordpress.org/plugins/tracking-code-manager IN HEAD//-->
    {{-- <script>
        (function(h, o, t, j, a, r) {
            h.hj = h.hj || function() {
                (h.hj.q = h.hj.q || []).push(arguments)
            };
            h._hjSettings = {
                hjid: 3208373,
                hjsv: 5
            };
            a = o.getElementsByTagName('head')[0];
            r = o.createElement('script');
            r.async = 1;
            r.src = t + h._hjSettings.hjid + j + h._hjSettings.hjsv;
            a.appendChild(r);
        })(window, document, '//static.hotjar.com/c/hotjar-', '.js?sv=');
    </script> --}}
    {{-- <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <script>
        document.addEventListener('DOMContentLoaded', function(event) {
            if (window.location.hash) {
                // Start at top of page
                window.scrollTo(0, 0);

                // Prevent default scroll to anchor by hiding the target element
                var db_hash_elem = document.getElementById(window.location.hash.substring(1));
                window.db_location_hash_style = db_hash_elem.style.display;
                db_hash_elem.style.display = 'none';

                // After a short delay, display the element and scroll to it
                jQuery(function($) {
                    setTimeout(function() {
                        $(window.location.hash).css('display', window.db_location_hash_style);
                        et_pb_smooth_scroll($(window.location.hash), false, 800);
                    }, 700);
                });
            }
        });
    </script>
    <link rel="icon" href="wp-content/uploads/2022/01/favicon-clinton.png" sizes="32x32" />
    <link rel="icon" href="wp-content/uploads/2022/01/favicon-clinton.png" sizes="192x192" />
    <link rel="apple-touch-icon" href="wp-content/uploads/2022/01/favicon-clinton.png" />
    <meta name="msapplication-TileImage"
        content="https://clintonmedicalpc.com/wp-content/uploads/2022/01/favicon-clinton.png" /> --}}

      <style id="et-critical-inline-css">
        /* @media only screen and (min-width:1350px) {
            .et_pb_row {
                padding: 27px 0
            }

            .et_pb_section {
                padding: 54px 0
            }

            .single.et_pb_pagebuilder_layout.et_full_width_page .et_post_meta_wrapper {
                padding-top: 81px
            }

            .et_pb_fullwidth_section {
                padding: 0
            }
        }

        .et_pb_section_0_tb_header.et_pb_section {
            padding-top: 0px;
            padding-bottom: 0px;
            margin-top: 0px;
            margin-bottom: 0px
        }

        .et_pb_row_0_tb_header,
        body #page-container .et-db #et-boc .et-l .et_pb_row_0_tb_header.et_pb_row,
        body.et_pb_pagebuilder_layout.single #page-container #et-boc .et-l .et_pb_row_0_tb_header.et_pb_row,
        body.et_pb_pagebuilder_layout.single.et_full_width_page #page-container #et-boc .et-l .et_pb_row_0_tb_header.et_pb_row {
            width: 90%;
            max-width: 1920px
        }

        .et_pb_image_0_tb_header {
            text-align: left;
            margin-left: 0
        }

        .et_pb_menu_0_tb_header.et_pb_menu ul li a {
            font-family: 'Armata', Helvetica, Arial, Lucida, sans-serif;
            font-size: 16px
        }

        .et_pb_menu_0_tb_header.et_pb_menu {
            background-color: #ffffff
        }

        .et_pb_menu_0_tb_header {
            padding-top: 40px
        }

        .et_pb_menu_0_tb_header.et_pb_menu .nav li ul,
        .et_pb_menu_0_tb_header.et_pb_menu .et_mobile_menu,
        .et_pb_menu_0_tb_header.et_pb_menu .et_mobile_menu ul {
            background-color: #ffffff !important
        }

        .et_pb_menu_0_tb_header .et_pb_menu_inner_container>.et_pb_menu__logo-wrap,
        .et_pb_menu_0_tb_header .et_pb_menu__logo-slot {
            width: auto;
            max-width: 100%
        }

        .et_pb_menu_0_tb_header .et_pb_menu_inner_container>.et_pb_menu__logo-wrap .et_pb_menu__logo img,
        .et_pb_menu_0_tb_header .et_pb_menu__logo-slot .et_pb_menu__logo-wrap img {
            height: auto;
            max-height: none
        } */

        /* .et_pb_menu_0_tb_header .mobile_nav .mobile_menu_bar:before,
        .et_pb_menu_0_tb_header .et_pb_menu__icon.et_pb_menu__search-button,
        .et_pb_menu_0_tb_header .et_pb_menu__icon.et_pb_menu__close-search-button,
        .et_pb_menu_0_tb_header .et_pb_menu__icon.et_pb_menu__cart-button {
            color: #7EBEC5
        }

        @media only screen and (max-width:980px) {
            .et_pb_image_0_tb_header .et_pb_image_wrap img {
                width: auto
            }
        }

        @media only screen and (max-width:767px) {
            .et_pb_image_0_tb_header .et_pb_image_wrap img {
                width: auto
            }
        }

        .et_pb_column_1_2 .et_pb_map,
        .et_pb_column_3_8 .et_pb_map {
            height: 520px
        }

        .et-pb-arrow-next {
            opacity: 1;
            right: 22px
        }

        .et-pb-arrow-prev {
            opacity: 1;
            left: 22px
        }

        .gform_body {
            padding-left: 15px !important;
            padding-right: 15px !important
        } */

        /* @media only screen and (min-width:641px) .gform_wrapper ul.gform_fields li.gfield {
            padding-right: 0px !important
        }

        .et_pb_section_0:before {
            z-index: 0
        }

        .et_pb_slider .et_pb_slide_1 .et_pb_slide_description .et_pb_button_wrapper,
        .et_pb_fullwidth_slider_0.et_pb_slider .et_pb_button_wrapper,
        .et_pb_slider .et_pb_slide_2 .et_pb_slide_description .et_pb_button_wrapper,
        .et_pb_slider .et_pb_slide_0 .et_pb_slide_description .et_pb_button_wrapper {
            text-align: center
        }

        .et_pb_slide_0,
        .et_pb_slide_1 {
            background-color: #ffffff
        }

        .et_pb_slide_2.et_pb_slide .et_pb_text_overlay_wrapper,
        .et_pb_slide_0.et_pb_slide .et_pb_text_overlay_wrapper,
        .et_pb_slide_1.et_pb_slide .et_pb_text_overlay_wrapper {
            background-color: rgba(0, 0, 0, 0.25)
        }

        .et_pb_slider .et_pb_slide_2.et_pb_slide:hover>.et_pb_container {
            width: 60%
        } */

        /* .et_pb_slide_2 {
            transition: width 300ms ease 0ms;
            background-color: #ffffff
        }

        .et_pb_fullwidth_slider_0.et_pb_slider .et_pb_slide_description .et_pb_slide_title {
            font-family: 'Montserrat', Helvetica, Arial, Lucida, sans-serif;
            font-weight: 800;
            color: #ffffff !important;
            letter-spacing: 1px;
            text-align: left
        }

        .et_pb_fullwidth_slider_0.et_pb_slider.et_pb_module .et_pb_slides .et_pb_slide_content {
            text-align: justify
        }

        .et_pb_fullwidth_slider_0 .et_pb_slide .et_pb_slide_description {
            text-align: left
        } */

        /* .et_pb_fullwidth_slider_0 .et_pb_slide_description,
        .et_pb_slider_fullwidth_off.et_pb_fullwidth_slider_0 .et_pb_slide_description {
            padding-top: 200px;
            padding-right: 20px;
            padding-bottom: 100px;
            padding-left: 20px
        }

        .et_pb_fullwidth_slider_0 {
            margin-bottom: 0px !important
        }

        body #page-container .et_pb_section .et_pb_fullwidth_slider_0.et_pb_slider .et_pb_more_button.et_pb_button {
            color: #FFFFFF !important;
            border-color: #28826d;
            border-radius: 50px;
            background-color: #28826d;
            padding-right: 20px !important;
            padding-left: 40px !important
        }

        body #page-container .et_pb_section .et_pb_fullwidth_slider_0.et_pb_slider .et_pb_more_button.et_pb_button:after {
            display: none
        } */

        /* body #page-container .et_pb_section .et_pb_fullwidth_slider_0.et_pb_slider .et_pb_more_button.et_pb_button:before {
            content: attr(data-icon);
            font-family: ETmodules !important;
            font-weight: 400 !important;
            color: #FFFFFF;
            line-height: inherit;
            font-size: inherit !important;
            opacity: 1;
            margin-left: -1.3em;
            right: auto;
            display: inline-block;
            font-family: ETmodules !important;
            font-weight: 400 !important
        }

        body #page-container .et_pb_section .et_pb_fullwidth_slider_0.et_pb_slider .et_pb_more_button.et_pb_button:hover:before {
            margin-left: .3em;
            right: auto;
            margin-left: -1.3em
        }

        .et_pb_fullwidth_slider_0.et_pb_slider {
            display: inherit !important
        }

        .et_pb_fullwidth_slider_0.et_pb_slider .et_pb_slide_description {
            margin-top: 150px;
            margin-bottom: 120px
        }

        .et_pb_fullwidth_slider_0.et_pb_slider.et_pb_slider .et_pb_slide .et_pb_slide_description a.et_pb_more_button.et_pb_button {
            float: left
        } */

        .et_pb_section_1.et_pb_section {
            padding-top: 0px;
            padding-right: 0px;
            padding-bottom: 0px;
            padding-left: 0px
        }

        .et_pb_section_1 {
            overflow: hidden
        }

        /* text color */
        .et_pb_cta_1.et_pb_promo h2,
        .et_pb_cta_1.et_pb_promo h1.et_pb_module_header,
        .et_pb_cta_1.et_pb_promo h3.et_pb_module_header,
        .et_pb_cta_1.et_pb_promo h4.et_pb_module_header,
        .et_pb_cta_1.et_pb_promo h5.et_pb_module_header,
        .et_pb_cta_1.et_pb_promo h6.et_pb_module_header,
        .et_pb_cta_0.et_pb_promo h2,
        .et_pb_cta_0.et_pb_promo h1.et_pb_module_header,
        .et_pb_cta_0.et_pb_promo h3.et_pb_module_header,
        .et_pb_cta_0.et_pb_promo h4.et_pb_module_header,
        .et_pb_cta_0.et_pb_promo h5.et_pb_module_header,
        .et_pb_cta_0.et_pb_promo h6.et_pb_module_header {
            font-weight: 700 !important;
            font-size: 30px !important;
            color: #ffffff !important;
            text-transform: uppercase;
        }

        .et_pb_cta_0.et_pb_promo .et_pb_promo_description div,
        .et_pb_cta_1.et_pb_promo .et_pb_promo_description div,
        .et_pb_cta_3.et_pb_promo .et_pb_promo_description div,
        .et_pb_cta_2.et_pb_promo .et_pb_promo_description div {
            font-size: 16px;
            color: #ffffff !important;
            line-height: 1.5em
        }

        /* .et_pb_cta_3.et_pb_promo,
        .et_pb_cta_0.et_pb_promo,
        .et_pb_cta_2.et_pb_promo,
        .et_pb_cta_1.et_pb_promo {
            padding-top: 20px !important;
            padding-right: 40px !important;
            padding-bottom: 20px !important;
            padding-left: 40px !important
        }

        .et_pb_cta_1.et_pb_promo .et_pb_promo_description h2,
        .et_pb_cta_2.et_pb_promo .et_pb_promo_description h2,
        .et_pb_cta_0.et_pb_promo .et_pb_promo_description h2,
        .et_pb_cta_3.et_pb_promo .et_pb_promo_description h2 {
            margin-bottom: 20px !important;
            font-weight: 700 !important
        } */

         /* text color */
        .et_pb_cta_2.et_pb_promo h2,
        .et_pb_cta_2.et_pb_promo h1.et_pb_module_header,
        .et_pb_cta_2.et_pb_promo h3.et_pb_module_header,
        .et_pb_cta_2.et_pb_promo h4.et_pb_module_header,
        .et_pb_cta_2.et_pb_promo h5.et_pb_module_header,
        .et_pb_cta_2.et_pb_promo h6.et_pb_module_header {
            font-weight: 700 !important;
            font-size: 30px !important;
            color: #ffffff !important;
            text-align: left !important;
            text-transform: uppercase;
        }

        .et_pb_cta_3.et_pb_promo h2,
        .et_pb_cta_3.et_pb_promo h1.et_pb_module_header,
        .et_pb_cta_3.et_pb_promo h3.et_pb_module_header,
        .et_pb_cta_3.et_pb_promo h4.et_pb_module_header,
        .et_pb_cta_3.et_pb_promo h5.et_pb_module_header,
        .et_pb_cta_3.et_pb_promo h6.et_pb_module_header {
            font-weight: 600 !important;
            font-size: 30px !important;
            color: #ffffff !important;
            text-transform: uppercase;
        }

        /* .et_pb_slider .et_pb_slide_1.et_pb_slide .et_pb_slide_description .et_pb_slide_title,
        .et_pb_slider .et_pb_slide_2.et_pb_slide .et_pb_slide_description .et_pb_slide_title,
        .et_pb_slider .et_pb_slide_0.et_pb_slide .et_pb_slide_description .et_pb_slide_title {
            font-family: 'Montserrat', Helvetica, Arial, Lucida, sans-serif !important;
            font-weight: 800 !important;
            font-size: 40px !important
        }

        .et_pb_slider.et_pb_module .et_pb_slide_0.et_pb_slide .et_pb_slide_description .et_pb_slide_content,
        .et_pb_slider.et_pb_module .et_pb_slide_1.et_pb_slide .et_pb_slide_description .et_pb_slide_content,
        .et_pb_slider.et_pb_module .et_pb_slide_2.et_pb_slide .et_pb_slide_description .et_pb_slide_content {
            font-family: 'Questrial', Helvetica, Arial, Lucida, sans-serif !important;
            font-size: 18px !important;
            text-shadow: 0.08em 0.08em 0.08em rgba(0, 0, 0, 0.4) !important
        } */

        /* .et_pb_slider .et_pb_slide_0 {
            background-image: url(https://clintonmedicalpc.com/wp-content/uploads/2022/01/home-bg-slider-1.jpg), linear-gradient(180deg, rgba(0, 0, 0, 0.06) 0%, rgba(0, 0, 0, 0.22) 71%)
        } */

        /* .et_pb_slider .et_pb_slide_1 .et_pb_slide_description,
        .et_pb_slider_fullwidth_off .et_pb_slide_1 .et_pb_slide_description {
            padding-top: 30px !important;
            padding-right: 40px !important;
            padding-bottom: 30px !important;
            padding-left: 400px !important
        } */
/*
        .et_pb_slider .et_pb_slide_2 {
            background-position: top center;
            background-blend-mode: overlay;
            background-color: initial;
            background-image: url(https://clintonmedicalpc.com/wp-content/uploads/2022/01/home-bg-slider-2.jpg), linear-gradient(180deg, rgba(0, 0, 0, 0.06) 0%, rgba(0, 0, 0, 0.22) 71%)
        } */

        .et_pb_column_0 {
            background-color: #155284;
            padding-top: 40px;
            padding-right: 0px;
            padding-bottom: 40px;
            padding-left: 0px
        }

        .et_pb_column_1 {
            background-color: #185C94;
            padding-top: 40px;
            padding-right: 0px;
            padding-bottom: 40px;
            padding-left: 0px
        }

        .et_pb_column_2 {
            background-color: #2D6DA2;
            padding-top: 40px;
            padding-right: 0px;
            padding-bottom: 40px;
            padding-left: 0px
        }

        .et_pb_column_3 {
            background-color: #397AAF;
            padding-top: 40px;
            padding-right: 0px;
            padding-bottom: 40px;
            padding-left: 0px
        }

        .et_pb_row_0.et_pb_row {
            padding-top: 0px !important;
            padding-right: 0px !important;
            padding-bottom: 0px !important;
            padding-left: 0px !important;
            margin-top: 0px !important;
            margin-bottom: 0px !important;
            margin-left: auto !important;
            margin-right: auto !important;
            padding-top: 0px;
            padding-right: 0px;
            padding-bottom: 0;
            padding-left: 0px
        }

        .et_pb_module.et_pb_text_align_left {
            text-align: left;
        }

        .et_pb_cta_3.et_pb_promo, .et_pb_cta_0.et_pb_promo, .et_pb_cta_2.et_pb_promo, .et_pb_cta_1.et_pb_promo {
          padding-top: 20px!important;
          padding-right: 40px!important;
          padding-bottom: 20px!important;
          padding-left: 40px!important;
        }

        @media only screen and (min-width:981px) {

            .et_pb_slider .et_pb_slide_0.et_pb_slide>.et_pb_container,
            .et_pb_slider .et_pb_slide_1.et_pb_slide>.et_pb_container,
            .et_pb_slider .et_pb_slide_2.et_pb_slide>.et_pb_container {
                width: 60%
            }

            .et_pb_slide_0 .et_pb_container,
            .et_pb_slide_2 .et_pb_container {
                float: left !important
            }

            .et_pb_slide_1 .et_pb_container {
                float: right !important
            }

            .et_pb_row_0,
            body #page-container .et-db #et-boc .et-l .et_pb_row_0.et_pb_row,
            body.et_pb_pagebuilder_layout.single #page-container #et-boc .et-l .et_pb_row_0.et_pb_row,
            body.et_pb_pagebuilder_layout.single.et_full_width_page #page-container #et-boc .et-l .et_pb_row_0.et_pb_row {
                width: 100%;
                max-width: 100%
            }
        }

        @media only screen and (max-width:980px) {

            .et_pb_slider .et_pb_slide_1.et_pb_slide>.et_pb_container,
            .et_pb_slider .et_pb_slide_0.et_pb_slide>.et_pb_container,
            .et_pb_slider .et_pb_slide_2.et_pb_slide>.et_pb_container {
                width: 80%
            }

            .et_pb_slide_0 .et_pb_container,
            .et_pb_slide_1 .et_pb_container,
            .et_pb_slide_2 .et_pb_container {
                float: none !important
            }

            .et_pb_fullwidth_slider_0 .et_pb_slide_description,
            .et_pb_slider_fullwidth_off.et_pb_fullwidth_slider_0 .et_pb_slide_description {
                padding-top: 70px;
                padding-right: 0px;
                padding-bottom: 70px;
                padding-left: 0px
            }

            .et_pb_fullwidth_slider_0 {
                margin-top: 0px !important;
                margin-right: 0px !important;
                margin-bottom: 0px !important;
                margin-left: 0px !important
            }

            body #page-container .et_pb_section .et_pb_fullwidth_slider_0.et_pb_slider .et_pb_more_button.et_pb_button:before {
                line-height: inherit;
                font-size: inherit !important;
                margin-left: -1.3em;
                right: auto;
                display: inline-block;
                opacity: 1;
                content: attr(data-icon);
                font-family: ETmodules !important;
                font-weight: 400 !important
            }

            body #page-container .et_pb_section .et_pb_fullwidth_slider_0.et_pb_slider .et_pb_more_button.et_pb_button:after {
                display: none
            }

            body #page-container .et_pb_section .et_pb_fullwidth_slider_0.et_pb_slider .et_pb_more_button.et_pb_button:hover:before {
                margin-left: .3em;
                right: auto;
                margin-left: -1.3em
            }

            .et_pb_row_0,
            body #page-container .et-db #et-boc .et-l .et_pb_row_0.et_pb_row,
            body.et_pb_pagebuilder_layout.single #page-container #et-boc .et-l .et_pb_row_0.et_pb_row,
            body.et_pb_pagebuilder_layout.single.et_full_width_page #page-container #et-boc .et-l .et_pb_row_0.et_pb_row {
                width: 100%;
                max-width: 100%
            }

            .et_pb_cta_3.et_pb_promo,
            .et_pb_cta_0.et_pb_promo,
            .et_pb_cta_1.et_pb_promo {
                padding-top: 20px !important;
                padding-right: 55px !important;
                padding-bottom: 20px !important;
                padding-left: 55px !important
            }

            .et_pb_cta_2.et_pb_promo {
                padding-top: 20px !important;
                padding-right: 55px !important;
                padding-bottom: 20px !important;
                padding-left: 55px !important;
                margin-top: 0px !important;
                margin-right: 0px !important;
                margin-bottom: 0px !important;
                margin-left: 0px !important
            }

            .et_pb_slider .et_pb_slide_1.et_pb_slide .et_pb_slide_description .et_pb_slide_title,
            .et_pb_slider .et_pb_slide_0.et_pb_slide .et_pb_slide_description .et_pb_slide_title,
            .et_pb_slider .et_pb_slide_2.et_pb_slide .et_pb_slide_description .et_pb_slide_title {
                font-size: 35px !important
            }

            .et_pb_slider .et_pb_slide_2 .et_pb_slide_description,
            .et_pb_slider_fullwidth_off .et_pb_slide_2 .et_pb_slide_description,
            .et_pb_slider .et_pb_slide_1 .et_pb_slide_description,
            .et_pb_slider_fullwidth_off .et_pb_slide_1 .et_pb_slide_description,
            .et_pb_slider .et_pb_slide_0 .et_pb_slide_description,
            .et_pb_slider_fullwidth_off .et_pb_slide_0 .et_pb_slide_description {
                padding-top: 0px !important;
                padding-right: 0px !important;
                padding-bottom: 0px !important;
                padding-left: 0px !important
            }
        }

        @media only screen and (max-width:767px) {

            .et_pb_slide_2 .et_pb_container,
            .et_pb_slide_0 .et_pb_container,
            .et_pb_slide_1 .et_pb_container {
                float: none !important
            }

            .et_pb_fullwidth_slider_0.et_pb_slider .et_pb_slide_description .et_pb_slide_title {
                font-size: 27px !important
            }

            body #page-container .et_pb_section .et_pb_fullwidth_slider_0.et_pb_slider .et_pb_more_button.et_pb_button:before {
                line-height: inherit;
                font-size: inherit !important;
                margin-left: -1.3em;
                right: auto;
                display: inline-block;
                opacity: 1;
                content: attr(data-icon);
                font-family: ETmodules !important;
                font-weight: 400 !important
            }

            body #page-container .et_pb_section .et_pb_fullwidth_slider_0.et_pb_slider .et_pb_more_button.et_pb_button:after {
                display: none
            }

            body #page-container .et_pb_section .et_pb_fullwidth_slider_0.et_pb_slider .et_pb_more_button.et_pb_button:hover:before {
                margin-left: .3em;
                right: auto;
                margin-left: -1.3em
            }

            .et_pb_row_0.et_pb_row {
                padding-top: 0px !important;
                padding-right: 0px !important;
                padding-bottom: 0px !important;
                padding-left: 0px !important;
                padding-top: 0px !important;
                padding-right: 0px !important;
                padding-bottom: 0px !important;
                padding-left: 0px !important
            }

            .et_pb_cta_2.et_pb_promo,
            .et_pb_cta_0.et_pb_promo,
            .et_pb_cta_3.et_pb_promo {
                padding-top: 20px !important;
                padding-right: 55px !important;
                padding-bottom: 20px !important;
                padding-left: 55px !important
            }

            .et_pb_cta_1.et_pb_promo {
                padding-right: 55px !important;
                padding-left: 55px !important
            }

            .et_pb_slider .et_pb_slide_0.et_pb_slide .et_pb_slide_description .et_pb_slide_title,
            .et_pb_slider .et_pb_slide_1.et_pb_slide .et_pb_slide_description .et_pb_slide_title,
            .et_pb_slider .et_pb_slide_2.et_pb_slide .et_pb_slide_description .et_pb_slide_title {
                font-size: 22px !important
            }

            .et_pb_slider .et_pb_slide_2 .et_pb_slide_description,
            .et_pb_slider_fullwidth_off .et_pb_slide_2 .et_pb_slide_description,
            .et_pb_slider .et_pb_slide_1 .et_pb_slide_description,
            .et_pb_slider_fullwidth_off .et_pb_slide_1 .et_pb_slide_description,
            .et_pb_slider .et_pb_slide_0 .et_pb_slide_description,
            .et_pb_slider_fullwidth_off .et_pb_slide_0 .et_pb_slide_description {
                padding-top: 0px !important;
                padding-right: 0px !important;
                padding-bottom: 0px !important;
                padding-left: 0px !important
            }
        }
      </style>


    {{-- inline div section css --}}

</head>
<body data-rsssl="1" class="home blog responsive-menu-slide-left">

   {{-- header --}}
    @include('user.layout.header')

    {{-- main content --}}
    @yield('content')

   {{-- footer --}}
    @include('user.layout.footer')


    <!--copyscapeskip-->


    <!--/copyscapeskip-->

    <button
      id="responsive-menu-button"
      tabindex="1"
      class="responsive-menu-button responsive-menu-boring"
      type="button"
      aria-label="Menu">

      <span class="responsive-menu-box">
        <span class="responsive-menu-inner"></span>
      </span>

      <span class="responsive-menu-label responsive-menu-label-bottom">
        <span class="responsive-menu-button-text">MENU</span>
      </span>

    </button>

    <div id="responsive-menu-container" class="slide-left">
      <div
        id="responsive-menu-wrapper"
        role="navigation"
        aria-label="main-responsive">
        <ul id="responsive-menu" class="">
          <li
            id="responsive-menu-item-17801"
            class="menu-item menu-item-type-custom menu-item-object-custom current-menu-item current_page_item menu-item-home responsive-menu-item responsive-menu-current-item"
            role="none">
            <a
              href="#"
              class="responsive-menu-item-link"
              tabindex="1"
              role="menuitem"
              >Home</a
            >
          </li>
          <li
            id="responsive-menu-item-17800"
            class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children responsive-menu-item responsive-menu-item-has-children"
            role="none"
          >
            <a
              href="#"
              class="responsive-menu-item-link"
              tabindex="1"
              role="menuitem">
              MOLECULAR/PRC
              <div class="responsive-menu-subarrow">▼</div></a>
            {{-- <ul
              aria-label="How it Works"
              role="menu"
              class="responsive-menu-submenu responsive-menu-submenu-depth-1">
              <li
                id="responsive-menu-item-17802"
                class="normal menu-item menu-item-type-post_type menu-item-object-page responsive-menu-item"
                role="none">
                <a
                  href="frequently-asked-questions/index.html"
                  class="responsive-menu-item-link"
                  tabindex="1"
                  role="menuitem">
                  FAQs</a>
              </li>
              <li
                id="responsive-menu-item-21253"
                class="menu-item menu-item-type-post_type menu-item-object-page responsive-menu-item"
                role="none">
                <a
                  href="schedule-an-appointment/index.html"
                  class="responsive-menu-item-link"
                  tabindex="1"
                  role="menuitem">
                  Schedule an Appointment</a>
              </li>
            </ul> --}}
          </li>
          <li
            id="responsive-menu-item-17804"
            class="the-franchise menu-item menu-item-type-post_type menu-item-object-page responsive-menu-item"
            role="none">
            <a
              href="{{ route('index') }}"
              class="responsive-menu-item-link"
              tabindex="1"
              role="menuitem">
              URINALYSIS</a>
          </li>
          <li
            id="responsive-menu-item-20989"
            class="covid menu-item menu-item-type-post_type menu-item-object-page responsive-menu-item"
            role="none">
            <a
              href="{{ route('index') }}"
              class="responsive-menu-item-link"
              tabindex="1"
              role="menuitem">
              GENETIC</a>
          </li>
          <li
            id="responsive-menu-item-17805"
            class="the-franchise menu-item menu-item-type-post_type menu-item-object-page responsive-menu-item"
            role="none">
            <a
              href="{{ route('index') }}"
              class="responsive-menu-item-link"
              tabindex="1"
              role="menuitem">
              ABOUT</a>
          </li>
          <li
            id="responsive-menu-item-17806"
            class="the-franchise menu-item menu-item-type-post_type menu-item-object-page responsive-menu-item"
            role="none">
            <a
              href="{{ route('index') }}"
              class="responsive-menu-item-link"
              tabindex="1"
              role="menuitem">
              CONTACT US</a>
          </li>
          {{-- <li
            id="responsive-menu-item-17807"
            class="the-franchise menu-item menu-item-type-post_type menu-item-object-page responsive-menu-item"
            role="none">
            <a
              href="std/index.html"
              class="responsive-menu-item-link"
              tabindex="1"
              role="menuitem">
              STD</a>
          </li>
          <li
            id="responsive-menu-item-17803"
            class="menu-item menu-item-type-post_type menu-item-object-page responsive-menu-item"
            role="none">
            <a
              href="company/business-solutions/index.html"
              class="responsive-menu-item-link"
              tabindex="1"
              role="menuitem">
              Business Solutions</a>
          </li>
          <li
            id="responsive-menu-item-17808"
            class="the-franchise menu-item menu-item-type-post_type menu-item-object-page responsive-menu-item"
            role="none">
            <a
              href="additional-tests/index.html"
              class="responsive-menu-item-link"
              tabindex="1"
              role="menuitem">
              Additional Tests</a>
          </li>
          <li
            id="responsive-menu-item-17809"
            class="lighter menu-item menu-item-type-custom menu-item-object-custom responsive-menu-item"
            role="none">
            <a target="_blank"
              href="franchise/index.html"
              class="responsive-menu-item-link"
              tabindex="1"
              role="menuitem">
              Franchisee Oportunities</a>
          </li>
          <li id="responsive-menu-item-17810"
            class="lighter menu-item menu-item-type-custom menu-item-object-custom responsive-menu-item"
            role="none">
            <a target="_blank"
              href="es/index.html"
              class="responsive-menu-item-link"
              tabindex="1"
              role="menuitem">
              ALTN en español</a>
          </li>
          <li
            id="responsive-menu-item-17811"
            class="corporate-site menu-item menu-item-type-custom menu-item-object-custom responsive-menu-item"
            role="none">
            <a
              href="#"
              class="responsive-menu-item-link"
              tabindex="1"
              role="menuitem"
              >Corporate Site</a
            >
          </li> --}}
        </ul>
      </div>
    </div>

    {{-- <script
      type="rocketlazyloadscript"
      data-rocket-type="text/javascript"
      id="rocket-browser-checker-js-after">

      "use strict";var _createClass=function(){function defineProperties(target,props){for(var i=0;i<props.length;i++){var descriptor=props[i];descriptor.enumerable=descriptor.enumerable||!1,descriptor.configurable=!0,"value"in descriptor&&(descriptor.writable=!0),Object.defineProperty(target,descriptor.key,descriptor)}}return function(Constructor,protoProps,staticProps){return protoProps&&defineProperties(Constructor.prototype,protoProps),staticProps&&defineProperties(Constructor,staticProps),Constructor}}();function _classCallCheck(instance,Constructor){if(!(instance instanceof Constructor))throw new TypeError("Cannot call a class as a function")}var RocketBrowserCompatibilityChecker=function(){function RocketBrowserCompatibilityChecker(options){_classCallCheck(this,RocketBrowserCompatibilityChecker),this.passiveSupported=!1,this._checkPassiveOption(this),this.options=!!this.passiveSupported&&options}return _createClass(RocketBrowserCompatibilityChecker,[{key:"_checkPassiveOption",value:function(self){try{var options={get passive(){return!(self.passiveSupported=!0)}};window.addEventListener("test",null,options),window.removeEventListener("test",null,options)}catch(err){self.passiveSupported=!1}}},{key:"initRequestIdleCallback",value:function(){!1 in window&&(window.requestIdleCallback=function(cb){var start=Date.now();return setTimeout(function(){cb({didTimeout:!1,timeRemaining:function(){return Math.max(0,50-(Date.now()-start))}})},1)}),!1 in window&&(window.cancelIdleCallback=function(id){return clearTimeout(id)})}},{key:"isDataSaverModeOn",value:function(){return"connection"in navigator&&!0===navigator.connection.saveData}},{key:"supportsLinkPrefetch",value:function(){var elem=document.createElement("link");return elem.relList&&elem.relList.supports&&elem.relList.supports("prefetch")&&window.IntersectionObserver&&"isIntersecting"in IntersectionObserverEntry.prototype}},{key:"isSlowConnection",value:function(){return"connection"in navigator&&"effectiveType"in navigator.connection&&("2g"===navigator.connection.effectiveType||"slow-2g"===navigator.connection.effectiveType)}}]),RocketBrowserCompatibilityChecker}();
    </script>

    <script type="text/javascript" id="rocket-preload-links-js-extra">
      /* <![CDATA[ */
      var RocketPreloadLinksConfig = {
        excludeUris:
          "\/(?:.+\/)?feed(?:\/(?:.+\/?)?)?$|\/(?:.+\/)?embed\/|(\/[^\/]+)?\/(index\\.php\/)?wp\\-json(\/.*|$)|\/refer\/|\/go\/|\/recommend\/|\/recommends\/",
        usesTrailingSlash: "1",
        imageExt:
          "jpg|jpeg|gif|png|tiff|bmp|webp|avif|pdf|doc|docx|xls|xlsx|php",
        fileExt:
          "jpg|jpeg|gif|png|tiff|bmp|webp|avif|pdf|doc|docx|xls|xlsx|php|html|htm",
        siteUrl: "https:\/\/www.anylabtestnow.com",
        onHoverDelay: "100",
        rateThrottle: "3",
      };
      /* ]]> */
    </script> --}}

    <script
      type="rocketlazyloadscript"
      data-rocket-type="text/javascript"
      id="rocket-preload-links-js-after">
      (function() {
      "use strict";var r="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},e=function(){function i(e,t){for(var n=0;n<t.length;n++){var i=t[n];i.enumerable=i.enumerable||!1,i.configurable=!0,"value"in i&&(i.writable=!0),Object.defineProperty(e,i.key,i)}}return function(e,t,n){return t&&i(e.prototype,t),n&&i(e,n),e}}();function i(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}var t=function(){function n(e,t){i(this,n),this.browser=e,this.config=t,this.options=this.browser.options,this.prefetched=new Set,this.eventTime=null,this.threshold=1111,this.numOnHover=0}return e(n,[{key:"init",value:function(){!this.browser.supportsLinkPrefetch()||this.browser.isDataSaverModeOn()||this.browser.isSlowConnection()||(this.regex={excludeUris:RegExp(this.config.excludeUris,"i"),images:RegExp(".("+this.config.imageExt+")$","i"),fileExt:RegExp(".("+this.config.fileExt+")$","i")},this._initListeners(this))}},{key:"_initListeners",value:function(e){-1<this.config.onHoverDelay&&document.addEventListener("mouseover",e.listener.bind(e),e.listenerOptions),document.addEventListener("mousedown",e.listener.bind(e),e.listenerOptions),document.addEventListener("touchstart",e.listener.bind(e),e.listenerOptions)}},{key:"listener",value:function(e){var t=e.target.closest("a"),n=this._prepareUrl(t);if(null!==n)switch(e.type){case"mousedown":case"touchstart":this._addPrefetchLink(n);break;case"mouseover":this._earlyPrefetch(t,n,"mouseout")}}},{key:"_earlyPrefetch",value:function(t,e,n){var i=this,r=setTimeout(function(){if(r=null,0===i.numOnHover)setTimeout(function(){return i.numOnHover=0},1e3);else if(i.numOnHover>i.config.rateThrottle)return;i.numOnHover++,i._addPrefetchLink(e)},this.config.onHoverDelay);t.addEventListener(n,function e(){t.removeEventListener(n,e,{passive:!0}),null!==r&&(clearTimeout(r),r=null)},{passive:!0})}},{key:"_addPrefetchLink",value:function(i){return this.prefetched.add(i.href),new Promise(function(e,t){var n=document.createElement("link");n.rel="prefetch",n.href=i.href,n.onload=e,n.onerror=t,document.head.appendChild(n)}).catch(function(){})}},{key:"_prepareUrl",value:function(e){if(null===e||"object"!==(void 0===e?"undefined":r(e))||!1 in e||-1===["http:","https:"].indexOf(e.protocol))return null;var t=e.href.substring(0,this.config.siteUrl.length),n=this._getPathname(e.href,t),i={original:e.href,protocol:e.protocol,origin:t,pathname:n,href:t+n};return this._isLinkOk(i)?i:null}},{key:"_getPathname",value:function(e,t){var n=t?e.substring(this.config.siteUrl.length):e;return n.startsWith("index.html")||(n="/"+n),this._shouldAddTrailingSlash(n)?n+"/":n}},{key:"_shouldAddTrailingSlash",value:function(e){return this.config.usesTrailingSlash&&!e.endsWith("index.html")&&!this.regex.fileExt.test(e)}},{key:"_isLinkOk",value:function(e){return null!==e&&"object"===(void 0===e?"undefined":r(e))&&(!this.prefetched.has(e.href)&&e.origin===this.config.siteUrl&&-1===e.href.indexOf("?")&&-1===e.href.indexOf("#")&&!this.regex.excludeUris.test(e.href)&&!this.regex.images.test(e.href))}}],[{key:"run",value:function(){"undefined"!=typeof RocketPreloadLinksConfig&&new n(new RocketBrowserCompatibilityChecker({capture:!0,passive:!0}),RocketPreloadLinksConfig).init()}}]),n}();t.run();
      }());
    </script>

    <script
      type="rocketlazyloadscript"
      data-minify="1"
      data-rocket-type="text/javascript"
      src="{{ asset('wp-content/cache/min/1/wp-content/themes/altn2018/js/testsearchebad.js?ver=1668177864') }}"
      id="testsearch-js"
      defer>
    </script>

    <script
      type="rocketlazyloadscript"
      data-rocket-type="text/javascript"
      src="{{ asset('wp-content/themes/altn2018/js/parallax.min4963.js?ver=1.1') }}"
      id="parallax-js"
      defer>
    </script>

    <script
      type="rocketlazyloadscript"
      data-minify="1"
      data-rocket-type="text/javascript"
      src="{{ asset('wp-content/cache/min/1/wp-content/themes/altn2018/js/sliderebad.js?ver=1668177864') }}"
      id="slider-js"
      defer>
    </script>

    <script
      type="rocketlazyloadscript"
      data-minify="1"
      data-rocket-type="text/javascript"
      src="{{ asset('wp-content/cache/min/1/wp-content/themes/altn2018/js/jquery.mousewheelebad.js?ver=1668177864') }}"
      id="mousewheel-js"
      defer>
    </script>

    {{-- <script type="text/javascript" id="jqueryui-js-js-extra">
      /* <![CDATA[ */
      var php_vars = {
        ajax_url: "https:\/\/www.anylabtestnow.com\/wp-admin\/admin-ajax.php",
        contacturl: "https:\/\/www.anylabtestnow.com\/contact-us\/",
        template_url:
          "https:\/\/www.anylabtestnow.com\/wp-content\/themes\/altn2018",
        principal_url: "https:\/\/www.anylabtestnow.com",
        franchise_url: "https:\/\/www.anylabtestnow.com",
        phone:
          '<a href="tel:18003844567" title="1-800-384-4567">1-800-384-4567<\/a>',
        max_tests: "8",
        blogid: "0",
      };
      var test_vars = [
        "10 Panel Instant Drug Screen (Saliva)",
        "10-Panel Instant*",
        "100 Food Sensitivity and Intolerance Test",
        "150 Food Sensitivity and Intolerance Test",
        "5-Panel Instant*",
        "50 Food Sensitivity and Intolerance Test",
        "9-Panel Instant*",
        "Addiction PGx Panel",
        "ADHD PGx Panel",
        "Advanced Drug Test Panel with Expanded Opiates and Fentanyl",
        "Advanced Thyroid Panel",
        "Alkaline Phosphatase (ALP)",
        "AMH Test",
        "Anemia Panel",
        "Annual Check-Up Panel",
        "Antinuclear Antibodies",
        "Arsenic Exposure (24 Hour Urine)",
        "Arsenic Exposure (Hair)",
        "Arthritis Screening Panel",
        "Avuncular Informational",
        "B12 and Folate",
        "Basic (Pediatric) Allergy Panel",
        "Basic Food Allergy Test",
        "Basic Food and Environmental Allergy Panel",
        "Basic Nutritional Panel",
        "Basic Saliva Hormone Testing Kit",
        "Basic STD Panel",
        "Basic Thyroid Panel",
        "Blood 5-Panel",
        "Blood PEth Alcohol Drug Test",
        "Blood Type and Rh Factor",
        "Breast Cancer Monitoring Panel",
        "Cadmium Exposure (24 Hour Urine)",
        "Cadmium Exposure (Hair)",
        "Carcinoembryonic Antigen (CEA)",
        "Cardiovascular PGx Panel",
        "Celiac Disease Panel",
        "Celiac, IBS, Crohn\u2019s Testing",
        "Cellular Nutritional Health (Micronutrient Test)",
        "Chlamydia",
        "Chlamydia and Gonorrhea",
        "Cholesterol (Lipid) Panel",
        "Cholesterol (Lipoprotein a)",
        "Cholesterol (Lipoprotein Particle Profile Plus)",
        "Cholesterol (Lipoprotein Particle Profile)",
        "Cholesterol Medication Maintenance Panel",
        "Chromium Exposure (Hair)",
        "Chromium Exposure (Serum)",
        "Clostridium Difficile (C. Diff) Stool Test",
        "Colon Cancer Screening (FIT) Test",
        "Complete Blood Count",
        "Complete Male and Female Saliva Hormone Testing Kit",
        "Comprehensive Female Panel",
        "Comprehensive Food and Environmental Allergy Panel",
        "Comprehensive Male Panel",
        "Comprehensive Male Panel Plus",
        "Comprehensive Metabolic Panel",
        "Comprehensive PGx Panel",
        "Comprehensive STD Platinum Female Panel",
        "Comprehensive STD Platinum Male Panel",
        "Comprehensive STD Plus",
        "Comprehensive Thyroid Panel",
        "Copper Exposure",
        "Coronavirus (COVID-19) IgG Antibody Test",
        "Coronavirus (COVID-19) IgM Antibody Test",
        "Coronavirus (COVID-19) IgM_IgG Rapid Antibody Test",
        "Coronavirus (COVID-19) IgM\/IgG Antibody Test",
        "CORONAVIRUS (COVID-19) IgM\/IgG\/IgA ANTIBODY TEST",
        "Coronavirus (COVID-19) Vaccine-Generated Antibody Test",
        "Cortisol Hormone Test (Saliva)",
        "Cortisol Test",
        "Cotinine (Nicotine Metabolite)",
        "COVID-19 and Flu Panel Rapid Antigen Test",
        "COVID-19 and Flu Panel Rapid Molecular \/ PCR Test",
        "COVID-19 Antigen Rapid Test",
        "COVID-19 Antigen Rapid Test Drive-Thru Option",
        "COVID-19 RT-PCR Nasal Swab \u2013 Expedited Next Day Results",
        "COVID-19 RT-PCR Saliva Test",
        "COVID-19 RT-PCR Swab \u2013 Expedited next day results",
        "COVID-19 RT-PCR Swab \u2013 Same day results",
        "COVID-19 RT-PCR Swab \u2013 Same Day Results in 1 \u2013 2 Hours",
        "COVID-19 RT-PCR Swab \u2013 Expedited (4 Hour) results",
        "COVID-19 RT-PCR Swab \u2013 Urgent results",
        "COVID-19 RT-PCR Swab Test",
        "COVID-19 RT-PCR Swab Test Airline Partner Only Discount",
        "Creatinine",
        "Cystic Fibrosis Screen",
        "Designer Drug Panel",
        "Dhea-Sulfate",
        "Diabetes Maintenance Panel",
        "DNA Detection",
        "Doggy Breed Test",
        "DXM Drug Test (OTC Cough Medicine)",
        "ECommerce Testing",
        "Electrolyte Panel",
        "Environmental Mold and Mycotoxin Assessment (EMMA)",
        "Erythrocyte Sedimentation Rate (ESR)",
        "Estradiol (E2) Test",
        "Expanded Food Allergy Panel",
        "Expanded Food Allergy Panel (IgG or IgE)",
        "Expanded Food Allergy Panel III \u2013 (Both IgE and IgG)",
        "Federal\/Regulated Drug",
        "Ferritin Test",
        "Folate",
        "Follicle Stimulating Hormone (FSH)",
        "Food and Environmental Comprehensive IgG Allergy Panel",
        "Food and Environmental Super Combo (IgE and IgG)",
        "Fourteen (14) Panel Drug Screen Including Fentanyl, K2 Alcohol (Oral Fluid)",
        "Free Thyroxine",
        "Free Triiodothyronine",
        "Gender Reveal",
        "Gliadin (Deaminated) Antibody (IgG) Test",
        "Glucose",
        "Gluten Allergy Tests",
        "Gonorrhea",
        "GPS Origins Ancestry Test",
        "Grandparentage Informational (Non-Legal)",
        "Grandparentage Legal",
        "Hair 10-Panel",
        "Hair 5-Panel Drug Test",
        "Hair 5-Panel Heavy Metals",
        "Hair 5-Panel With Expanded Opiate Drug Test",
        "Hair Alcohol Test",
        "Hair Child 5-Panel Drug Exposure Test",
        "Hair Child 7-Panel Drug Exposure Test",
        "Hair Child 9-Panel Drug Exposure Test",
        "Hair Unknown Chemicals\/Toxins",
        "Hair Unknown Substance",
        "Healthcare Professional Drug Panel",
        "Healthy Weight DNA Test",
        "Heart Health Panel",
        "Hemoglobin A1C (Diabetes Monitoring)",
        "Hepatic Function Panel",
        "Hepatitis A",
        "Hepatitis B (Immunity Only)",
        "Hepatitis B (STD)",
        "Hepatitis C",
        "Hepatitis Panel",
        "Herpes 1 and 2",
        "High Sensitivity C-Reactive Protein Cardiac",
        "HIV",
        "HIV-1 Qualitative, RNA (Early Detection) Test",
        "Homocysteine Test",
        "Human Papillomavirus (HPV) \u2013 Female",
        "Human Papillomavirus (HPV) \u2013 Male",
        "Instant EtG Screen (Urine)",
        "Instant Urine Cotinine (Nicotine) Screen",
        "Intolerance And Sensitivity (200 Food Panel)",
        "Iodine Blood Test",
        "Iron Blood Test",
        "K2\/Spice",
        "Lead Blood Test",
        "Lead Exposure (Hair)",
        "Lithium Level Blood Test",
        "Luteinizing Hormone (LH)",
        "Lyme Disease",
        "Magnesium Test",
        "Maternity Informational",
        "Maternity Legal",
        "Measles",
        "Mental Health PGx Panel",
        "Mercury Exposure (24 Hour Urine)",
        "MMA \/ Boxing Fighter Panel",
        "MMR Titer Testing",
        "Mold (Mycotoxin) Testing",
        "Mold (Sensitivity And Intolerance Testing)",
        "Mold Allergy Panel",
        "Mononucleosis (Mono) Test",
        "MTHFR PGx Panel",
        "Mumps",
        "Nail 10-Panel Drug Test",
        "Nail 5-Panel Drug Test",
        "Non Invasive Prenatal Legal (NIPP)",
        "Osteoporosis Risk Panel",
        "Ova and Parasite Test",
        "Pain PGx Panel",
        "Pancreatic Amylase",
        "Pancreatic Lipase",
        "Paternity Informational (Non-Legal)",
        "Paternity Legal",
        "Phenytoin (Dilantin)",
        "Phosphate Test",
        "Potassium Test",
        "Pre-Operative Panel (Female)",
        "Pre-Operative Panel (Male)",
        "Pregnancy Test (Yes or No)",
        "Pregnancy With Gestational Age (How Far Along)",
        "Prenatal OB Panel",
        "Progesterone",
        "Prolactin",
        "Prostate Specific Antigen (PSA)",
        "Prostate Specific Antigen (PSA) Free and Total",
        "Prothrombin Time (Clotting Test)",
        "PT\/INR Clotting Screen",
        "QuantiFERON\u00ae TB GOLD PLUS",
        "Rabies Titer",
        "Rapid Flu (Influenza) Test",
        "Rapid RSV (Respiratory Syncytial Virus) Test",
        "Rapid Strep Test",
        "Renal Function Panel",
        "Reverse T3 (rT3) Test",
        "Rheumatoid Factor",
        "Rubella",
        "Saliva Adrenal Testing Kit",
        "Semen Detection",
        "Sensitivity and Intolerance Testing (Platinum Comprehensive)",
        "Sensitivity and Intolerance Testing (Platinum Plus)",
        "Siblingship Informational (Non-Legal)",
        "Siblingship Legal",
        "Sickle Cell Screen",
        "Skin Vitality Panel Kit",
        "Sleep Balance Kit",
        "STD Panel, Comprehensive",
        "Student Titer I Panel",
        "Student Titer II Panel",
        "Synthetic Drug Panel",
        "Syphilis",
        "T4 Total and Thyroxine Total",
        "Testosterone",
        "Testosterone Panel (Continued-Therapy)",
        "Testosterone Panel (Pre-Therapy)",
        "Thyroglobulin Antibody Test",
        "Thyroid Element Kit",
        "Thyroid Peroxidase Antibodies",
        "Thyroid Stimulating Hormone",
        "TIBC (Total Iron Binding Capacity)",
        "Trichomoniasis",
        "Trio Informational (Non-Legal)",
        "Trio Legal",
        "Tuberculosis (TB) Skin Test",
        "Tuberculosis Blood Screen (TB)",
        "Twelve (12) Panel Rapid Drug Screen, Including Fentanyl",
        "Two-Step Tuberculosis (TB) Skin Test",
        "Uric Acid",
        "Urinalysis (Routine Checkup)",
        "Urine 10-Panel Drug Test",
        "Urine 10-Panel Expanded Opiates Drug Test",
        "Urine 10-Panel Expanded Opiates W\/Ecstasy Drug Test",
        "Urine 10-Panel w\/Ecstasy Drug Test",
        "Urine 10-Panel With Cotinine",
        "Urine 5-Panel Drug Test W\/Expanded Opiate",
        "Urine 5-Panel Standard",
        "Urine 5-Panel W\/EtG Alcohol",
        "Urine 5-Panel w\/ETOH Alcohol",
        "Urine 9-Panel Drug Test W\/Expanded Opiate",
        "Urine 9-Panel W\/EtG Alcohol",
        "Urine 9-Panel w\/ETOH Alcohol",
        "Urine Culture",
        "Urine EtG Alcohol",
        "Urine ETOH Alcohol",
        "Urine Heroin Drug Test",
        "Urine Marijuana Confirmation",
        "Urine Steroid",
        "Urine Unknown Substance",
        "Vaping Panel (Hair)",
        "Vaping Panel (Urine)",
        "Varicella Virus (Chicken Pox)",
        "Vitamin B12",
        "Vitamin D",
        "Weight Management Kit",
      ];
      var location_vars = [
        "Abilene - 3351 Turner Plaza Drive, 108A, Abilene, TX 79606",
        "Albuquerque - 2305 San Pedro Dr. NE, Suite D1, Albuquerque, NM 87110",
        "Allentown - 5924 Tilghman Street, Suite E, Allentown, PA 18104",
        "Alpharetta - 5530 Windward Pkwy, #1030, Alpharetta, GA 30004",
        "Amarillo - 201 Westgate Pkwy, Suite L   , Amarillo, TX 79121",
        "Arlington - 4645 Matlock Road, Suite 104, Arlington, TX 76018",
        "Ashburn - 42775 Generation Drive, Ashburn, VA 20147",
        "Augusta - 3328 Washington Rd, Suite 1-C, Augusta, GA 30907",
        "Austell - 3875 Austell Rd., Suite 202, Austell, GA 30106",
        "Central Austin - 5523 Balcones Dr, Austin, TX 78731",
        "Westlake - 6317 Bee Caves Rd, Suite 210, Austin, TX 78746",
        "South Austin - 9500 S IH 35, Suite L-750, Austin, TX 78748",
        "Aventura - 17138 W. Dixie Hwy., Aventura, FL 33160",
        "Avon - 7810 E. US Highway 36, Suite B, Avon, IN 46123",
        "Ballwin - 14071 Manchester Road, Ballwin, MO 63011",
        "Baton Rouge - 14635 S. Harrell's Ferry Rd., Unit 3C, Baton Rouge, LA 70816",
        "Baytown - 2215 Rollingbrook Drive Suite 120, Baytown, TX 77521",
        "Beaumont - 6755 Phelan Blvd., Beaumont, TX 77706",
        "Bellingham - 1225 E Sunset Dr, Suite 155, Bellingham, WA 98226",
        "Birmingham - 2409 Acton Road, #105, Birmingham, AL 35243",
        "Boca Raton - 23016 Sandalfoot Plaza Dr., Boca Raton, FL 33428",
        "Boerne - 1369 South Main St., Suite 105, Boerne, TX 78006",
        "Bossier City - 2121 Airline Drive, Suite 200, Bossier City, LA 71111",
        "Bowling Green - 2425 Scottsville Rd, Suite 108, Bowling Green, KY 42104",
        "Bradenton - 7242 55th Avenue East, Bradenton, FL 34203",
        "Brandon - 205 E Brandon Blvd, Suite B, Brandon, FL 33511",
        "Buckhead - Buckhead Store Location 2221 Peachtree Rd. NE, Atlanta, GA 30309",
        "Burleson - 671 NE Alsbury Blvd, Burleson, TX 76028",
        "Canton - 4782 Dressler Rd. NW, Canton, OH 44718",
        "Carmel - 13636 N. Meridian Street, Carmel, IN 46032",
        "Cary - 8204 Tryon Woods Dr. Suite 107, Cary, NC 27518",
        "Cedar Hill - 294 Uptown Boulevard , Cedar Hill, TX 75104",
        "Cedar Park - 10900 Lakeline Mall Dr Ste 550, Austin, TX 78717",
        "Denver-Centennial - 10909 East Arapahoe Pl, Suite #103, Centennial, CO 80112",
        "Chandler - 2075 W Warner Rd., Suite #2, Chandler, AZ 85224",
        "Charleston - 1836 Ashley River Road, Suite E, Charleston, SC 29407",
        "Charlotte - 8318-708 Pineville Matthews Road, Charlotte, NC 28226",
        "Charlotte - 10106 Benfield Rd. Suite 103, Charlotte, NC 28269",
        "Chattanooga - 7155 Lee Hwy. Suite 400, Chattanooga, TN 37421",
        "Chesterfield - 14624 Hancock Village Street, Chesterfield, VA 23832",
        "Clarksville - 2257 Wilma Rudolph Blvd., Suite D, Clarksville, TN 37040",
        "Clarksville - 1305 Veterans Parkway, Clarksville, IN 47129",
        "Clearwater - 3135 Florida 580, Suite 12, Safety Harbor, FL 34695",
        "College Station - 3505 Longmire Drive, Suite C, College Station, TX 77845",
        "Colorado Springs S. Nevada - 1835 S. Nevada Avenue, Colorado Springs, CO 80906",
        "Colorado Springs Academy Ave - 7828 N. Academy Blvd., Colorado Springs, CO 80920",
        "Columbia - 285 Columbiana Dr., Suite H, Columbia, SC 29212",
        "Columbus - 5450 Whittlesey Blvd, Suite 8, Columbus, GA 31909",
        "Coral Springs - 955 University Drive, Coral Springs, FL 33071",
        "Corpus Christi - 5417 Everhart Road, Corpus Christi, TX 78411",
        "Covington - 360 Emerald Forest Blvd., Suite H, Covington, LA 70433",
        "Dallas - 5219 W. Lovers Lane, Suite A, Dallas, TX 75209",
        "North Dallas - 11930 Preston Road, #120, Dallas, TX 75230",
        "Daphne - 2200 Highway 98, Suite 5B, Daphne, AL 36526",
        "Davie - 4343 S State Rd 7, Davie, FL 33314",
        "Emory - 2062 North Decatur Rd., Decatur, GA 30033",
        "Delray Beach - 5175 West Atlantic Avenue, Suite D, Delray Beach, FL 33484",
        "Denton - 2215 S. Loop 288, Suite 408, Denton, TX 76205",
        "Denver-Central Park - 7505 E 35th Avenue, Unit 375, Denver, CO 80238",
        "Doral - 9851 Northwest 58th Street, #116, Doral, FL 33178",
        "Gwinnett - Gwinnett Store Location. 3665 Club Drive, Suite 103A, Duluth, GA 30096",
        "Durham - 105 W. NC Hwy. 54, Suite 245, Durham, NC 27713",
        "El Paso - 7933 North Mesa St, El Paso, TX 79932",
        "Erlanger - 3413 Dixie Highway, Erlanger, KY 41018",
        "Eugene - 977 Garfield Street #6, Eugene, OR 97402",
        "Euless - 1060 North Main Street, Suite 106, Euless, TX 76039",
        "Everett - 1205 SE Everett Mall Way, Suite D, Everett, WA 98208",
        "Fairfax - 2670D Avenir Place, Vienna, VA 22031",
        "Fargo - 3019 13th Avenue South, Suite B, Fargo, ND 58103",
        "Fishers - 7818 E. 96th Street, Fishers, IN 46037",
        "Flower Mound - 1221 Flower Mound Rd., Ste. 310, Flower Mound, TX 75028",
        "Forest Park - 1104 Kemper Meadow Drive, Forest Park, OH 45240",
        "Fort Mill - 729 Crossroads Plaza, Suite 7, Fort Mill, SC 29708",
        "Fort Worth - 5512 Bellaire Drive South. Suite J, Country Day Plaza, Fort Worth, TX 76109",
        "Fort Worth - 2700 Western Center Blvd., Suite 100, Fort Worth, TX 76131",
        "Franklin - 1735 Galleria Blvd., #1057, Franklin, TN 37067",
        "Fredericksburg - 1135 Emancipation Hwy, Fredericksburg, VA 22401",
        "Frisco - 3520 Preston Road, Suite 113A, Frisco, TX 75034",
        "Ft. Lauderdale - 4242 North Federal Highway, Ste. A, Ft. Lauderdale, FL 33308",
        "Ft Myers - 13401-9 Summerlin Rd., Ft Myers, FL 33919",
        "Ft. Wayne - 915 E. Dupont Rd, Ft. Wayne, IN 46825",
        "Garland - 3046 Lavon Dr., Ste 120, Garland, TX 75040",
        "Glen Allen - 11446 W Broad Street, Glen Allen, VA 23060",
        "Glendale - 18205 N. 51st Avenue, Suite 143, Glendale, AZ 85308",
        "Granbury - 3306 E US HWY 377, Granbury, TX 76049",
        "Greenville - 1140 Woodruff Road, Suite #107, Greenville, SC 29607",
        "Greenwood - 1642 South Olive Branch Parke Lane, Suite 900, Greenwood, IN 46143",
        "Hallandale - 1452 E Hallandale Beach Blvd, Hallandale, FL 33009",
        "Harker Heights - 201 E Central Texas Expy., #640, Harker Heights, TX 76548",
        "Houma - 5922 West Main Street, Ste. B1, Houma, LA 70360",
        "Northloop - 2902 N. Shepherd Dr., Suite E, Houston, TX 77008",
        "River Oak - 1005 Waugh Drive, Suite E, Houston, TX 77019",
        "Medical Center - 2282 W Holcombe Blvd., Houston, TX 77030",
        "Katy Freeway - 9742 Katy Freeway, Suite D-200, Houston, TX 77055",
        "Houston Galleria - 5901 Westheimer Rd, Suite W, Houston, TX 77063",
        "Willowbrook - 17557 Tomball Parkway (Hwy. 249), Houston, TX 77064",
        "Northwest - 13141 FM1960, Suite 500, Houston, TX 77065",
        "Houston Royal Oaks - 11807 Westheimer, Suite 560, Houston, TX 77077",
        "Katy - 21929 Katy Freeway, Houston, TX 77450",
        "Huntersville - 14231 Market Square Drive, Suite C2, Huntersville, NC 28078",
        "Independence - 18921 East Valley View Pkwy Ste. E, Independence, MO 64055",
        "Indian Trail - 622 Indian Trail Road S, Indian Trail, NC 28079",
        "Indianapolis - 911 N. East Street, Indianapolis, IN 46202",
        "Irving - 2540 N Belt Line Rd., Irving, TX 75062",
        "North Jacksonville - 725 Skymarks Drive, Suite #8, Jacksonville, FL 32218",
        "Jacksonville - 13170 Atlantic Blvd Suite 60, Jacksonville, FL 32225",
        "Jacksonville South - 9965 San Jose Blvd Ste 30, Jacksonville, FL 32257",
        "Jonesboro - 6681 Jonesboro Rd., Suite 104, Morrow, GA 30260",
        "Kendall - 7436 SW 117 Ave., Miami, FL 33183",
        "Town Center - 440 Ernest W Barrett Pkwy NW Suite 61, Kennesaw, GA 30144",
        "Kingwood - 30129 Rock Creek Dr, suite 900, Kingwood, TX 77339",
        "Kissimmee - 1325 East Vine Street, Kissimmee, FL 34744",
        "Knoxville - 1645 Downtown West. Suite #31, Knoxville, TN 37919",
        "Lafayette - 5530 Johnston St., Suite 200 , Lafayette, LA 70503",
        "Lake Charles - 110 W Prien Lake Road, Lake Charles, LA 70601",
        "Lake Havasu City - 30 South Acoma, Lake Havasu City, AZ 86403",
        "Lakeville - 20200 Heritage Drive, Lakeville, MN 55044",
        "Lakeway - 1516 Ranch Road 620 South, Suite #120, Lakeway, TX 78734",
        "Newtown - 2 Summit Square Center, Suite G, Langhorne, PA 19047",
        "Largo - 13847 Walsingham Rd. Suite J, Largo, FL 33774",
        "Summerlin - 9360 W. Flamingo Road, Suite 105, Las Vegas, NV 89147",
        "League City - 2910 Gulf Freeway South, Suite A-1, League City, TX 77573",
        "Lititz - 235 Bloomfield Dr., 110 Bldg. B, Lititz, PA 17543",
        "Little Rock - 301 North Shackleford Road, Suite B3, Little Rock, AR 72211",
        "Livonia - 37112 Six Mile Rd. Unit B11, Livonia, MI 48152",
        "Longmont - 1240 Ken Pratt Blvd Unit #8, Longmont, CO 80501",
        "Longwood - 2401 West State Rd 434  Suite 163, Longwood, FL 32779",
        "Louisville - Shelbyville Road Plaza. 4600 Shelbyville Rd, Suite 306, Louisville, KY 40207",
        "Loves Park - 6254 East Riverside Blvd., Loves Park, IL 61111",
        "Lubbock - 5217 82nd Street, Unit 102A, Lubbock, TX 79424",
        "McComb - 1121 HWY 98\/51 Suite A Summit, McComb, MS 39666",
        "McKinney - 1705 W University Drive (Hwy. 380). Suite 112, McKinney, TX 75069",
        "Medina - 5155 Buehlers Dr., Suite 102, Medina, OH 44256",
        "Memphis - 5075 Park Avenue, Memphis, TN 38117",
        "Merritt Island - 543 N. Courtenay Pkwy., Merritt Island, FL 32953",
        "Mesa - 2048 East Baseline Rd., Suite C-5, Mesa, AZ 85204",
        "Mesquite - 3434 Towne Crossing, Suite 106B, Mesquite, TX 75150",
        "Metairie - 3213 17th Street, Suite 10, Metairie, LA 70002",
        "Midland - 1913 Heritage Blvd., Midland, TX 79707",
        "Miramar - 9909 Miramar Parkway, Miramar, FL 33025",
        "Mt. Pleasant - 1136 Hungryneck Blvd., Suite D, Mt. Pleasant, SC 29464",
        "Murfreesboro - 1790 W Northfield Blvd, Murfreesboro, TN 37129",
        "Murray - 5616 South 900 East, Murray, UT 84121",
        "Myrtle Beach - 5401 Dick Pond Rd. , Myrtle Beach, SC 29588",
        "Nashville - 114 29th Avenue N., Nashville, TN 37203",
        "Newark - 430 Peoples Plaza, Newark, DE 19702",
        "New Braunfels - 244 FM 306, Suite 122, New Braunfels, TX 78130",
        "Norman - 3408 36th Ave. NW, Suite 104, Norman, OK 73072",
        "North Olmsted - 28951 Lorain Rd, Suite B, North Olmsted, OH 44070",
        "Dallas - 6333 E. Mockingbird Lane, Ste. 121, Dallas, TX 75214",
        "West Orlando - 8975 West Colonial Drive, Ocoee, FL 34761",
        "Oklahoma City - 14600 North Pennsylvania Avenue Suite B, Oklahoma City, OK 73134",
        "Orlando - 570 N. Alafaya Trail #116, Orlando, FL 32828",
        "Ormond Beach - 1425 Hand Ave, Suite E, Ormond Beach, FL 32174",
        "Overland Park - 6507 W 119th Street, Overland Park, KS 66209",
        "Palm Beach Gardens - 4206A Northlake Blvd., Palm Beach Gardens, FL 33410",
        "Palm Springs - 1676 Congress Ave, Palm Springs, FL 33461",
        "Panama City - 330 West 23rd Street, Suite H , Panama City, FL 32405",
        "Pearland - 2802 Business Center Dr., Suite 110, Pearland, TX 77584",
        "Pensacola - 4761-2 Bayou Blvd., Pensacola, FL 32503",
        "Phoenix - 4501 E. Thomas Road, #105, Phoenix, AZ 85018",
        "Ahwatukee - 4025 E Chandler Blvd, Suite 54, Phoenix, AZ 85048",
        "Pinecrest - 12679 S. Dixie Hwy, Pinecrest, FL 33156",
        "Plano - 4701 W. Park Blvd., Suite 206, Plano, TX 75093",
        "Plantation - 8367 W Sunrise Blvd., Plantation, FL 33322",
        "Plymouth - 4345 Nathan Lane North, Suite G, Plymouth, MN 55442",
        "Portland - 8118 Beaverton Hillsdale Hwy., Portland, OR 97225",
        "Potomac Falls - 20804 Edds Lane, Potomac Falls, VA 20165",
        "Raleigh - 6401 Triangle Plantation Dr., Suite D-103, Raleigh, NC 27616",
        "Ralston - 5362 S 72nd St., Ralston, NE 68127",
        "Redmond - 16150 NE 85th St, #107, Redmond, WA 98052",
        "Richardson - 515 W Campbell Rd, #107, Richardson, TX 75080",
        "Rochester Hills - 2070 West Auburn Rd, Rochester Hills, MI 48309",
        "Rockwall - 714 E I30, Rockwall, TX 75087",
        "Rosenberg - 1730 B.F. Terry Blvd., Suite 702, Rosenberg, TX 77471",
        "Round Rock - 110 N IH 35, Suite 260, Round Rock, TX 78681",
        "Royal Oak - 30357 Woodward Avenue, Royal Oak, MI 48073",
        "San Angelo - 3270 Sherwood Way, San Angelo, TX 76901",
        "San Antonio - 4219 McCullough Ave., Ste B, San Antonio, TX 78212",
        "San Antonio - 8348-2 Marbach Road, San Antonio, TX 78227",
        "San Antonio - North Central San Antonio. 17700 San Pedro Avenue, Suite # 300, San Antonio, TX 78232",
        "San Antonio - 5720 Bandera Rd., Suite 6, San Antonio, TX 78238",
        "San Antonio - 5238 De Zavala Road, Suite #134, San Antonio, TX 78249",
        "San Antonio - 9910 West Loop 1604 Suite 105, San Antonio, TX 78254",
        "San Marcos - 1941 S. Interstate 35, Suite 113, San Marcos, TX 78666",
        "Sandy Springs - 6309 Roswell Rd. NE, #2E, Atlanta, GA 30328",
        "Sarasota - 4141 South Tamiami Trail, Suite 24, Sarasota, FL 34231",
        "Schertz - 17460 I.H. 35 North, Suite 400, Schertz, TX 78154",
        "Scottsdale - 8989 E. Via Linda, Suite 111, Scottsdale, AZ 85258",
        "Shenandoah - 19073 I-45 Suite 110, Shenandoah, TX 77385",
        "Shreveport - 5737 Youree Drive, Shreveport, LA 71105",
        "Mishawaka - 313 W. University Dr., Mishawaka, IN 46545",
        "Southlake - 500 W. Southlake Blvd #134, Southlake, TX 76092",
        "Springfield - 131 S. State Road, Springfield, PA 19064",
        "Springfield - 1921 E. Independence Street, Springfield, MO 65804",
        "St. George - 175 W. 900 S., #5, St. George, UT 84770",
        "St. Petersburg - 6812 22nd Ave North , St. Petersburg, FL 33710",
        "Stuart - 1296 Nw Federal Hwy, Stuart, FL 34994",
        "Sugarland - 15910-C Lexington Blvd, Sugar Land, TX 77479",
        "Tacoma - 4916 Center Street, Suite C, Tacoma, WA 98409",
        "Tampa - 3937 W. Kennedy Blvd., Tampa, FL 33609",
        "Thornton - 881 Thornton Pkwy, Thornton, CO 80229",
        "Tucson - 7187 East Tanque Verde Rd., Tucson, AZ 85715",
        "Tulsa - 3807-D South Peoria Ave, Tulsa, OK 74105",
        "Tyler - 535 WSW Loop 323, Suite 206, Tyler, TX 75701",
        "Vancouver - 5201 East Fourth Plain Blvd., Suite 105, Vancouver, WA 98661",
        "Venice - 1846 South Tamiami Trail #11, Venice, FL 34293",
        "Vernon Hills - 701 N. Milwaukee Ave, Suite 336, Vernon Hills, IL 60061",
        "Victoria - 5309 N Navarro St, Victoria, TX 77904",
        "Villa Park - 100 E. Roosevelt Road, Suite 42, Villa Park, IL 60181",
        "Wauwatosa - The Promenade Shopping Center. 857 N. Mayfair Road, Wauwatosa, WI 53226",
        "Weatherford - 169 College Park, Weatherford, TX 76086",
        "Wesley Chapel - 27421 Wesley Chapel Blvd., Wesley Chapel, FL 33544",
        "West Des Moines - 1821 22nd Street, Suite #105, West Des Moines, IA 50266",
        "West Melbourne - 145 Palm Bay Rd. NE. Suite 102, West Melbourne, FL 32904",
        "West Palm Beach - 4350 Okeechobee Blvd., West Palm Beach, FL 33409",
        "Wichita - 7777 E 21st St N #130, Wichita, KS 67206",
        "Wichita Falls - 3916 Kemp Blvd., Suite J1, Wichita Falls, TX 76308",
        "Wilmington - 1319 Military Cutoff Rd., Suite D, Wilmington, NC 28405",
        "Winter Park - 501 North Orlando Ave., Suite 151, Winter Park, FL 32789",
        "Woodway - 8810 Woodway Drive, Suite 301, Woodway, TX 76712",
      ];
      var labs_vars = {
        DFH5DS: { name: "Radiance Diagnostics", latitude: "", longitude: "" },
        XCIIT5: { name: "Naveris", latitude: "", longitude: "" },
        MHRDD4: { name: "Billion To One", latitude: "", longitude: "" },
        "3DDF4F": { name: "Natera", latitude: "", longitude: "" },
        CSGHGY: { name: "Myriad Genetics", latitude: "", longitude: "" },
        FNM5D3: {
          name: "Diagnostic Support Services (DS2)",
          latitude: "",
          longitude: "",
        },
        JDRR21: { name: "Grail", latitude: "", longitude: "" },
      };
      /* ]]> */
    </script> --}}

    <script
      type="rocketlazyloadscript"
      data-rocket-type="text/javascript"
      src="{{ asset('wp-content/themes/altn2018/js/jquery-ui.min0028.js?ver=1.13.1') }}"
      id="jqueryui-js-js"
      defer>
    </script>

    <script
      type="rocketlazyloadscript"
      data-minify="1"
      data-rocket-type="text/javascript"
      src="{{ asset('wp-content/cache/min/1/wp-content/themes/altn2018/js/cartebad.js?ver=1668177864') }}"
      id="cart-js"
      defer >
    </script>

    {{-- <script type="text/javascript" id="moove_gdpr_frontend-js-extra">
      /* <![CDATA[ */
      var moove_frontend_gdpr_scripts = {
        ajaxurl: "https:\/\/www.anylabtestnow.com\/wp-admin\/admin-ajax.php",
        post_id: "21612",
        plugin_dir:
          "https:\/\/www.anylabtestnow.com\/wp-content\/plugins\/gdpr-cookie-compliance",
        show_icons: "all",
        is_page: "",
        strict_init: "1",
        enabled_default: { third_party: 1, advanced: 1 },
        geo_location: "false",
        force_reload: "false",
        is_single: "",
        hide_save_btn: "false",
        current_user: "0",
        cookie_expiration: "365",
        script_delay: "2000",
        close_btn_action: "1",
        close_cs_action: "1",
        gdpr_scor: "true",
        wp_lang: "",
        gdpr_consent_version: "1",
        gdpr_uvid: "4e9c51a1e3f2a3e4d37cb533376d852b",
        stats_enabled: "",
        gdpr_aos_hide: "false",
        consent_log_enabled: "",
        enable_on_scroll: "false",
      };
      /* ]]> */
    </script> --}}

    <script
      type="rocketlazyloadscript"
      data-minify="1"
      data-rocket-type="text/javascript"
      src="{{ asset('wp-content/cache/min/1/wp-content/plugins/gdpr-cookie-compliance/dist/scripts/mainebad.js?ver=1668177864"
      id="moove_gdpr_frontend-js') }}"
      defer
    ></script>

    <script
      type="rocketlazyloadscript"
      data-rocket-type="text/javascript"
      id="moove_gdpr_frontend-js-after">
      var gdpr_consent__strict = "true"
      var gdpr_consent__thirdparty = "true"
      var gdpr_consent__advanced = "true"
      var gdpr_consent__cookies = "strict|thirdparty|advanced"
    </script>

    <script
      type="rocketlazyloadscript"
      data-minify="1"
      data-rocket-type="text/javascript"
      src="{{ asset('wp-content/cache/min/1/wp-content/plugins/gdpr-cookie-compliance-addon/assets/js/gdpr_cc_addonebad.js?ver=1668177864') }}"
      id="gdpr_cc_addon_frontend-js"
      defer
    ></script>

    <!--copyscapeskip-->
    <!-- V1 -->
    {{-- <div
      id="moove_gdpr_cookie_modal"
      class="gdpr_lightbox-hide"
      role="complementary"
      aria-label="GDPR Settings Screen">
      <div
        class="moove-gdpr-modal-content moove-clearfix logo-position-left moove_gdpr_modal_theme_v1">
        <button
          class="moove-gdpr-modal-close"
          aria-label="Close GDPR Cookie Settings"
        >
          <span class="gdpr-sr-only">Close GDPR Cookie Settings</span>
          <span class="gdpr-icon moovegdpr-arrow-close"></span>
        </button>
        <div class="moove-gdpr-modal-left-content">
          <div class="moove-gdpr-company-logo-holder">
            <img
              src="{{ asset('wp-content/plugins/gdpr-cookie-compliance/dist/images/gdpr-logo.png') }}"
              alt="Full Service Lab Testing Clinics Near You | Any Lab Test Now"
              width="350"
              height="233"
              class="img-responsive"
            />
          </div>
          <!--  .moove-gdpr-company-logo-holder -->
          <ul id="moove-gdpr-menu">
            <li
              class="menu-item-on menu-item-privacy_overview menu-item-selected"
            >
              <button
                data-href="#privacy_overview"
                class="moove-gdpr-tab-nav"
                aria-label="Privacy Overview"
              >
                <span class="gdpr-nav-tab-title">Privacy Overview</span>
              </button>
            </li>

            <li class="menu-item-strict-necessary-cookies menu-item-off">
              <button
                data-href="#strict-necessary-cookies"
                class="moove-gdpr-tab-nav"
                aria-label="Strictly Necessary Cookies"
              >
                <span class="gdpr-nav-tab-title"
                  >Strictly Necessary Cookies</span
                >
              </button>
            </li>

            <li class="menu-item-off menu-item-third_party_cookies">
              <button
                data-href="#third_party_cookies"
                class="moove-gdpr-tab-nav"
                aria-label="Performance cookies"
              >
                <span class="gdpr-nav-tab-title">Performance cookies</span>
              </button>
            </li>

            <li class="menu-item-advanced-cookies menu-item-off">
              <button
                data-href="#advanced-cookies"
                class="moove-gdpr-tab-nav"
                aria-label="Functional &amp; Targeting cookies"
              >
                <span class="gdpr-nav-tab-title"
                  >Functional &amp; Targeting cookies</span
                >
              </button>
            </li>

            <li class="menu-item-moreinfo menu-item-off">
              <button
                data-href="#cookie_policy_modal"
                class="moove-gdpr-tab-nav"
                aria-label="Cookie Policy"
              >
                <span class="gdpr-nav-tab-title">Cookie Policy</span>
              </button>
            </li>
          </ul>

          <div class="moove-gdpr-branding-cnt">
            <a
              href="https://wordpress.org/plugins/gdpr-cookie-compliance/"
              target="_blank"
              rel="noopener noreferrer nofollow"
              class="moove-gdpr-branding"
              >Powered by&nbsp; <span>GDPR Cookie Compliance</span></a
            >
          </div>
          <!--  .moove-gdpr-branding -->
        </div>
        <!--  .moove-gdpr-modal-left-content -->
        <div class="moove-gdpr-modal-right-content">
          <div class="moove-gdpr-modal-title"></div>
          <!-- .moove-gdpr-modal-ritle -->
          <div class="main-modal-content">
            <div class="moove-gdpr-tab-content">
              <div id="privacy_overview" class="moove-gdpr-tab-main">
                <span class="tab-title">Privacy Overview</span>
                <div class="moove-gdpr-tab-main-content">
                  <p>
                    This website uses cookies so that we can provide you with
                    the best user experience possible. Cookie information is
                    stored in your browser and performs functions such as
                    recognizing you when you return to our website and helping
                    our team to understand which sections of the website you
                    find most interesting and useful.
                  </p>
                </div>
                <!--  .moove-gdpr-tab-main-content -->
              </div>
              <!-- #privacy_overview -->
              <div
                id="strict-necessary-cookies"
                class="moove-gdpr-tab-main"
                style="display: none"
              >
                <span class="tab-title">Strictly Necessary Cookies</span>
                <div class="moove-gdpr-tab-main-content">
                  <p>
                    These cookies are required to move around a website and use
                    its features, such as browsing as a registered visitor, or
                    making a purchase. Our website uses strictly necessary
                    cookies. You may be able to block these cookies through your
                    browser settings, but some parts of the websites may not
                    function properly. These cookies do not store any personally
                    identifiable information.
                  </p>
                  <div class="moove-gdpr-status-bar">
                    <div class="gdpr-cc-form-wrap">
                      <div class="gdpr-cc-form-fieldset">
                        <label
                          class="cookie-switch"
                          for="moove_gdpr_strict_cookies"
                        >
                          <span class="gdpr-sr-only"
                            >Enable or Disable Cookies</span
                          >
                          <input
                            type="checkbox"
                            aria-label="Strictly Necessary Cookies"
                            value="check"
                            name="moove_gdpr_strict_cookies"
                            id="moove_gdpr_strict_cookies"
                          />
                          <span
                            class="cookie-slider cookie-round"
                            data-text-enable="Enabled"
                            data-text-disabled="Disabled"
                          ></span>
                        </label>
                      </div>
                      <!-- .gdpr-cc-form-fieldset -->
                    </div>
                    <!-- .gdpr-cc-form-wrap -->
                  </div>
                  <!-- .moove-gdpr-status-bar -->
                  <div
                    class="moove-gdpr-strict-warning-message"
                    style="margin-top: 10px"
                  >
                    <p>
                      If you disable this cookie, we will not be able to save
                      your preferences. This means that every time you visit
                      this website you will need to enable or disable cookies
                      again.
                    </p>
                  </div>
                  <!--  .moove-gdpr-tab-main-content -->
                </div>
                <!--  .moove-gdpr-tab-main-content -->
              </div>
              <!-- #strict-necesarry-cookies -->

              <div
                id="third_party_cookies"
                class="moove-gdpr-tab-main"
                style="display: none"
              >
                <span class="tab-title">Performance cookies</span>
                <div class="moove-gdpr-tab-main-content">
                  <p>
                    These cookies collect information about how visitors use a
                    website, such as the number of visitors browsing the site or
                    a particular section of the site, the pages visitors go to
                    most often, and whether they get error messages from web
                    pages. These cookies do not collect information that
                    identifies a specific visitor. Rather, the information these
                    cookies collect is aggregated and therefore anonymous. The
                    information that we gather through performance cookies is
                    used to improve how the websites work, to help us evaluate
                    website usage, makes our marketing more relevant, and
                    improves your experience.
                  </p>
                  <p>
                    Tools that perform website analytics, such as Google
                    Analytics, use performance cookies to generate reports about
                    a website's traffic and the sources of that traffic. They
                    can also be used to recognize you across platforms and
                    devices. These tools tell website owners how many visitors
                    came to their website, and whether those visitors came to
                    the website after clicking on a link in a search engine or
                    via another referral source. In this way, the website is
                    able to evaluate website traffic as well as the ways to
                    increase website traffic. Like virtually all websites, we
                    use performance cookies, including Google Analytics.
                  </p>
                  <div class="moove-gdpr-status-bar">
                    <div class="gdpr-cc-form-wrap">
                      <div class="gdpr-cc-form-fieldset">
                        <label
                          class="cookie-switch"
                          for="moove_gdpr_performance_cookies"
                        >
                          <span class="gdpr-sr-only"
                            >Enable or Disable Cookies</span
                          >
                          <input
                            type="checkbox"
                            aria-label="Performance cookies"
                            value="check"
                            name="moove_gdpr_performance_cookies"
                            id="moove_gdpr_performance_cookies"
                            disabled
                          />
                          <span
                            class="cookie-slider cookie-round"
                            data-text-enable="Enabled"
                            data-text-disabled="Disabled"
                          ></span>
                        </label>
                      </div>
                      <!-- .gdpr-cc-form-fieldset -->
                    </div>
                    <!-- .gdpr-cc-form-wrap -->
                  </div>
                  <!-- .moove-gdpr-status-bar -->
                  <div
                    class="moove-gdpr-strict-secondary-warning-message"
                    style="margin-top: 10px; display: none"
                  >
                    <p>
                      Please enable Strictly Necessary Cookies first so that we
                      can save your preferences!
                    </p>
                  </div>
                  <!--  .moove-gdpr-tab-main-content -->
                </div>
                <!--  .moove-gdpr-tab-main-content -->
              </div>
              <!-- #third_party_cookies -->

              <div
                id="advanced-cookies"
                class="moove-gdpr-tab-main"
                style="display: none"
              >
                <span class="tab-title">Functional & Targeting cookies</span>
                <div class="moove-gdpr-tab-main-content">
                  <p>
                    Functional cookies: These cookies enable our website to
                    remember choices made by website visitors (such as user
                    name, language or region) and provide enhanced, more
                    personal features. They may be set by us or by third-party
                    providers whose services we have added to our website. This
                    allows websites to improve user experience. We use
                    functional cookies.
                  </p>
                  <p>
                    Targeting cookies: These cookies are used to deliver online
                    advertisements both on and off websites visited that may be
                    more relevant to visitor interests based on activity from a
                    website being browsed and based on a profile built of
                    visitor interests. They may also be used to limit the number
                    of times visitors see an advertisement and to help measure
                    the effectiveness of advertising campaigns. We use targeting
                    cookies. We do not serve third-party advertisements to you
                    on our websites. But, we do set targeting cookies on our
                    sites to help us promote Quest’s products and services to
                    you on other sites and platforms. We also allow third
                    parties to place cookies on our websites, and information
                    collected via these cookies is used to provide you with
                    information that may be of interest to you based on your
                    activities on our websites. These targeting cookies do not
                    directly store personal information (such as names or email
                    addresses), but store unique identifiers that identify to us
                    and our partners a particular visitor’s browser and/or
                    device. Please visit our <a
                      title="Privacy Statement"
                      href="privacy-statement/index.html"
                      target="_blank"
                      rel="noopener"
                      >Privacy Statement</a
                    > for more information about the cookies used across our
                    websites and to manage your cookie preferences where
                    available.
                  </p>
                  <div class="moove-gdpr-status-bar">
                    <div class="gdpr-cc-form-wrap">
                      <div class="gdpr-cc-form-fieldset">
                        <label
                          class="cookie-switch"
                          for="moove_gdpr_advanced_cookies"
                        >
                          <span class="gdpr-sr-only"
                            >Enable or Disable Cookies</span
                          >
                          <input
                            type="checkbox"
                            aria-label="Functional & Targeting cookies"
                            value="check"
                            name="moove_gdpr_advanced_cookies"
                            id="moove_gdpr_advanced_cookies"
                            disabled
                          />
                          <span
                            class="cookie-slider cookie-round"
                            data-text-enable="Enabled"
                            data-text-disabled="Disabled"
                          ></span>
                        </label>
                      </div>
                      <!-- .gdpr-cc-form-fieldset -->
                    </div>
                    <!-- .gdpr-cc-form-wrap -->
                  </div>
                  <!-- .moove-gdpr-status-bar -->
                  <div
                    class="moove-gdpr-strict-secondary-warning-message"
                    style="margin-top: 10px; display: none"
                  >
                    <p>
                      Please enable Strictly Necessary Cookies first so that we
                      can save your preferences!
                    </p>
                  </div>
                  <!--  .moove-gdpr-strict-secondary-warning-message -->
                </div>
                <!--  .moove-gdpr-tab-main-content -->
              </div>
              <!-- #advanced-cookies -->

              <div
                id="cookie_policy_modal"
                class="moove-gdpr-tab-main"
                style="display: none"
              >
                <span class="tab-title">Cookie Policy</span>
                <div class="moove-gdpr-tab-main-content">
                  <p>
                    More information about our
                    <a
                      href="privacy-statement/index.html"
                      target="_blank"
                      rel="noopener"
                      >Cookie Policy</a
                    >
                  </p>
                </div>
                <!--  .moove-gdpr-tab-main-content -->
              </div>
            </div>
            <!--  .moove-gdpr-tab-content -->
          </div>
          <!--  .main-modal-content -->
          <div class="moove-gdpr-modal-footer-content">
            <div class="moove-gdpr-button-holder">
              <button
                class="mgbutton moove-gdpr-modal-allow-all button-visible"
                role="button"
                aria-label="Enable All"
              >
                Enable All
              </button>
              <button
                class="mgbutton moove-gdpr-modal-save-settings button-visible"
                role="button"
                aria-label="Save Settings"
              >
                Save Settings
              </button>
            </div>
            <!--  .moove-gdpr-button-holder -->
          </div>
          <!--  .moove-gdpr-modal-footer-content -->
        </div>
        <!--  .moove-gdpr-modal-right-content -->

        <div class="moove-clearfix"></div>
      </div>
      <!--  .moove-gdpr-modal-content -->
    </div> --}}
    <!-- #moove_gdpr_cookie_modal -->
    <!--/copyscapeskip-->
    <script>
      window.lazyLoadOptions = {
        elements_selector: "iframe[data-lazy-src]",
        data_src: "lazy-src",
        data_srcset: "lazy-srcset",
        data_sizes: "lazy-sizes",
        class_loading: "lazyloading",
        class_loaded: "lazyloaded",
        threshold: 300,
        callback_loaded: function (element) {
          if (
            element.tagName === "IFRAME" &&
            element.dataset.rocketLazyload == "fitvidscompatible"
          ) {
            if (element.classList.contains("lazyloaded")) {
              if (typeof window.jQuery != "undefined") {
                if (jQuery.fn.fitVids) {
                  jQuery(element).parent().fitVids();
                }
              }
            }
          }
        },
      };
      window.addEventListener(
        "LazyLoad::Initialized",
        function (e) {
          var lazyLoadInstance = e.detail.instance;
          if (window.MutationObserver) {
            var observer = new MutationObserver(function (mutations) {
              var image_count = 0;
              var iframe_count = 0;
              var rocketlazy_count = 0;
              mutations.forEach(function (mutation) {
                for (var i = 0; i < mutation.addedNodes.length; i++) {
                  if (
                    typeof mutation.addedNodes[i].getElementsByTagName !==
                    "function"
                  ) {
                    continue;
                  }
                  if (
                    typeof mutation.addedNodes[i].getElementsByClassName !==
                    "function"
                  ) {
                    continue;
                  }
                  images = mutation.addedNodes[i].getElementsByTagName("img");
                  is_image = mutation.addedNodes[i].tagName == "IMG";
                  iframes =
                    mutation.addedNodes[i].getElementsByTagName("iframe");
                  is_iframe = mutation.addedNodes[i].tagName == "IFRAME";
                  rocket_lazy =
                    mutation.addedNodes[i].getElementsByClassName(
                      "rocket-lazyload"
                    );
                  image_count += images.length;
                  iframe_count += iframes.length;
                  rocketlazy_count += rocket_lazy.length;
                  if (is_image) {
                    image_count += 1;
                  }
                  if (is_iframe) {
                    iframe_count += 1;
                  }
                }
              });
              if (image_count > 0 || iframe_count > 0 || rocketlazy_count > 0) {
                lazyLoadInstance.update();
              }
            });
            var b = document.getElementsByTagName("body")[0];
            var config = { childList: !0, subtree: !0 };
            observer.observe(b, config);
          }
        },
        !1
      );
    </script>

    <script
      data-no-minify="1"
      async
      src="{{ asset('wp-content/plugins/wp-rocket/assets/js/lazyload/17.5/lazyload.min.js') }}">
    </script>

    <script type="rocketlazyloadscript" data-rocket-type="text/javascript">
      if(typeof dataLayer !== 'undefined')
      {
      	dataLayer.push({'ANYLABLocation': 1});
      }
    </script>

    <div id="schedule-attention" class="hide">
      <div class="square"></div>
    </div>

    <link rel="preconnect" href="https://fonts.googleapis.com/" />
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />

    <script
      type="rocketlazyloadscript"
      src="{{ asset('kit.fontawesome.com/50996de306.js') }}"
      crossorigin="anonymous"
      defer>
    </script>
  </body>
</html>
