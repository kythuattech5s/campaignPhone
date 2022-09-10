"use strict";
var __assign = (this && this.__assign) || function () {
    __assign = Object.assign || function(t) {
        for (var s, i = 1, n = arguments.length; i < n; i++) {
            s = arguments[i];
            for (var p in s) if (Object.prototype.hasOwnProperty.call(s, p))
                t[p] = s[p];
        }
        return t;
    };
    return __assign.apply(this, arguments);
};
var __spreadArray = (this && this.__spreadArray) || function (to, from, pack) {
    if (pack || arguments.length === 2) for (var i = 0, l = from.length, ar; i < l; i++) {
        if (ar || !(i in from)) {
            if (!ar) ar = Array.prototype.slice.call(from, 0, i);
            ar[i] = from[i];
        }
    }
    return to.concat(ar || Array.prototype.slice.call(from));
};
exports.__esModule = true;
exports.isRule = exports.createId = exports.isComponent = exports.isEmptyObj = exports.isObject = exports.appendStyles = exports.setViewEl = exports.getViewEl = exports.capitalize = exports.getUnitFromValue = exports.getPointerEvent = exports.normalizeFloat = exports.shallowDiff = exports.getElement = exports.isEnterKey = exports.isEscKey = exports.getKeyChar = exports.getKeyCode = exports.isTextNode = exports.camelCase = exports.getElRect = exports.getModel = exports.matches = exports.upFirst = exports.hasDnd = exports.off = exports.on = exports.buildBase64UrlFromSvg = exports.deepMerge = exports.escape = exports.find = exports.isTaggableNode = exports.isCommentNode = exports.getUiClass = exports.toLowerCase = exports.getGlobal = exports.hasWin = exports.isDef = void 0;
var underscore_1 = require("underscore");
var isDef = function (value) { return typeof value !== 'undefined'; };
exports.isDef = isDef;
var hasWin = function () { return typeof window !== 'undefined'; };
exports.hasWin = hasWin;
var getGlobal = function () {
    return typeof globalThis !== 'undefined' ? globalThis : typeof window !== 'undefined' ? window : global;
};
exports.getGlobal = getGlobal;
var toLowerCase = function (str) { return (str || '').toLowerCase(); };
exports.toLowerCase = toLowerCase;
var elProt = (0, exports.hasWin)() ? window.Element.prototype : {};
// @ts-ignore
var matches = elProt.matches || elProt.webkitMatchesSelector || elProt.mozMatchesSelector || elProt.msMatchesSelector;
exports.matches = matches;
// @ts-ignore
var getUiClass = function (em, defCls) {
    var _a = em.getConfig(), stylePrefix = _a.stylePrefix, customUI = _a.customUI;
    return [customUI && "".concat(stylePrefix, "cui"), defCls].filter(function (i) { return i; }).join(' ');
};
exports.getUiClass = getUiClass;
/**
 * Import styles asynchronously
 * @param {String|Array<String>} styles
 */
var appendStyles = function (styles, opts) {
    if (opts === void 0) { opts = {}; }
    var stls = (0, underscore_1.isArray)(styles) ? __spreadArray([], styles, true) : [styles];
    if (stls.length) {
        var href = stls.shift();
        if (href && (!opts.unique || !document.querySelector("link[href=\"".concat(href, "\"]")))) {
            var head = document.head;
            var link = document.createElement('link');
            link.href = href;
            link.rel = 'stylesheet';
            if (opts.prepand) {
                head.insertBefore(link, head.firstChild);
            }
            else {
                head.appendChild(link);
            }
        }
        appendStyles(stls);
    }
};
exports.appendStyles = appendStyles;
/**
 * Returns shallow diff between 2 objects
 * @param  {Object} objOrig
 * @param  {Objec} objNew
 * @return {Object}
 * @example
 * var a = {foo: 'bar', baz: 1, faz: 'sop'};
 * var b = {foo: 'bar', baz: 2, bar: ''};
 * shallowDiff(a, b);
 * // -> {baz: 2, faz: null, bar: ''};
 */
var shallowDiff = function (objOrig, objNew) {
    var result = {};
    var keysNew = (0, underscore_1.keys)(objNew);
    for (var prop in objOrig) {
        if (objOrig.hasOwnProperty(prop)) {
            var origValue = objOrig[prop];
            var newValue = objNew[prop];
            if (keysNew.indexOf(prop) >= 0) {
                if (origValue !== newValue) {
                    result[prop] = newValue;
                }
            }
            else {
                result[prop] = null;
            }
        }
    }
    for (var prop in objNew) {
        if (objNew.hasOwnProperty(prop)) {
            if ((0, underscore_1.isUndefined)(objOrig[prop])) {
                result[prop] = objNew[prop];
            }
        }
    }
    return result;
};
exports.shallowDiff = shallowDiff;
var on = function (el, ev, fn, opts) {
    var evs = ev.split(/\s+/);
    el = el instanceof Array ? el : [el];
    var _loop_1 = function (i) {
        el.forEach(function (elem) { return elem && elem.addEventListener(evs[i], fn, opts); });
    };
    for (var i = 0; i < evs.length; ++i) {
        _loop_1(i);
    }
};
exports.on = on;
var off = function (el, ev, fn, opts) {
    var evs = ev.split(/\s+/);
    el = el instanceof Array ? el : [el];
    var _loop_2 = function (i) {
        el.forEach(function (elem) { return elem && elem.removeEventListener(evs[i], fn, opts); });
    };
    for (var i = 0; i < evs.length; ++i) {
        _loop_2(i);
    }
};
exports.off = off;
var getUnitFromValue = function (value) {
    return value.replace(parseFloat(value), '');
};
exports.getUnitFromValue = getUnitFromValue;
var upFirst = function (value) { return value[0].toUpperCase() + value.toLowerCase().slice(1); };
exports.upFirst = upFirst;
var camelCase = function (value) {
    return value.replace(/-./g, function (x) { return x[1].toUpperCase(); });
};
exports.camelCase = camelCase;
var normalizeFloat = function (value, step, valueDef) {
    if (step === void 0) { step = 1; }
    if (valueDef === void 0) { valueDef = 0; }
    var stepDecimals = 0;
    if (isNaN(value))
        return valueDef;
    value = parseFloat(value);
    if (Math.floor(value) !== value) {
        var side = step.toString().split('.')[1];
        stepDecimals = side ? side.length : 0;
    }
    return stepDecimals ? parseFloat(value.toFixed(stepDecimals)) : value;
};
exports.normalizeFloat = normalizeFloat;
var hasDnd = function (em) {
    return 'draggable' in document.createElement('i') && (em ? em.get('Config').nativeDnD : 1);
};
exports.hasDnd = hasDnd;
/**
 * Ensure to fetch the element from the input argument
 * @param  {HTMLElement|Component} el Component or HTML element
 * @return {HTMLElement}
 */
var getElement = function (el) {
    if ((0, underscore_1.isElement)(el) || isTextNode(el)) {
        return el;
        // @ts-ignore
    }
    else if (el && el.getEl) {
        // @ts-ignore
        return el.getEl();
    }
};
exports.getElement = getElement;
/**
 * Check if element is a text node
 * @param  {HTMLElement} el
 * @return {Boolean}
 */
var isTextNode = function (el) { return el && el.nodeType === 3; };
exports.isTextNode = isTextNode;
/**
 * Check if element is a comment node
 * @param  {HTMLElement} el
 * @return {Boolean}
 */
var isCommentNode = function (el) { return el && el.nodeType === 8; };
exports.isCommentNode = isCommentNode;
/**
 * Check if element is a comment node
 * @param  {HTMLElement} el
 * @return {Boolean}
 */
var isTaggableNode = function (el) { return el && !isTextNode(el) && !(0, exports.isCommentNode)(el); };
exports.isTaggableNode = isTaggableNode;
var find = function (arr, test) {
    var result = null;
    arr.some(function (el, i) { return (test(el, i, arr) ? ((result = el), 1) : 0); });
    return result;
};
exports.find = find;
var escape = function (str) {
    if (str === void 0) { str = ''; }
    return "".concat(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;')
        .replace(/`/g, '&#96;');
};
exports.escape = escape;
var deepMerge = function () {
    var args = [];
    for (var _i = 0; _i < arguments.length; _i++) {
        args[_i] = arguments[_i];
    }
    var target = __assign({}, args[0]);
    for (var i = 1; i < args.length; i++) {
        var source = __assign({}, args[i]);
        for (var key in source) {
            var targValue = target[key];
            var srcValue = source[key];
            if (isObject(targValue) && isObject(srcValue)) {
                target[key] = (0, exports.deepMerge)(targValue, srcValue);
            }
            else {
                target[key] = srcValue;
            }
        }
    }
    return target;
};
exports.deepMerge = deepMerge;
/**
 * Ensure to fetch the model from the input argument
 * @param  {HTMLElement|Component} el Component or HTML element
 * @return {Component}
 */
var getModel = function (el, $) {
    var model = el;
    if (!$ && el && el.__cashData) {
        model = el.__cashData.model;
    }
    else if ((0, underscore_1.isElement)(el)) {
        model = $(el).data('model');
    }
    return model;
};
exports.getModel = getModel;
var getElRect = function (el) {
    var def = {
        top: 0,
        left: 0,
        width: 0,
        height: 0
    };
    if (!el)
        return def;
    var rectText;
    if (isTextNode(el)) {
        var range = document.createRange();
        range.selectNode(el);
        rectText = range.getBoundingClientRect();
        range.detach();
    }
    return rectText || (el.getBoundingClientRect ? el.getBoundingClientRect() : def);
};
exports.getElRect = getElRect;
/**
 * Get cross-device pointer event
 * @param  {Event} ev
 * @return {PointerEvent}
 */
var getPointerEvent = function (ev) {
    // @ts-ignore
    return ev.touches && ev.touches[0] ? ev.touches[0] : ev;
};
exports.getPointerEvent = getPointerEvent;
/**
 * Get cross-browser keycode
 * @param  {Event} ev
 * @return {Number}
 */
var getKeyCode = function (ev) { return ev.which || ev.keyCode; };
exports.getKeyCode = getKeyCode;
var getKeyChar = function (ev) { return String.fromCharCode(getKeyCode(ev)); };
exports.getKeyChar = getKeyChar;
var isEscKey = function (ev) { return getKeyCode(ev) === 27; };
exports.isEscKey = isEscKey;
var isEnterKey = function (ev) { return getKeyCode(ev) === 13; };
exports.isEnterKey = isEnterKey;
var isObject = function (val) { return val !== null && !Array.isArray(val) && typeof val === 'object'; };
exports.isObject = isObject;
var isEmptyObj = function (val) { return Object.keys(val).length <= 0; };
exports.isEmptyObj = isEmptyObj;
var capitalize = function (str) { return str && str.charAt(0).toUpperCase() + str.substring(1); };
exports.capitalize = capitalize;
var isComponent = function (obj) { return obj && obj.toHTML; };
exports.isComponent = isComponent;
var isRule = function (obj) { return obj && obj.toCSS; };
exports.isRule = isRule;
var getViewEl = function (el) { return el.__gjsv; };
exports.getViewEl = getViewEl;
var setViewEl = function (el, view) {
    el.__gjsv = view;
};
exports.setViewEl = setViewEl;
var createId = function (length) {
    if (length === void 0) { length = 16; }
    var result = '';
    var chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var len = chars.length;
    for (var i = 0; i < length; i++) {
        result += chars.charAt(Math.floor(Math.random() * len));
    }
    return result;
};
exports.createId = createId;
var buildBase64UrlFromSvg = function (svg) {
    if (svg && svg.substr(0, 4) === '<svg') {
        var base64Str = '';
        if ((0, exports.hasWin)()) {
            base64Str = window.btoa(svg);
        }
        else if (typeof Buffer !== 'undefined') {
            base64Str = Buffer.from(svg, 'utf8').toString('base64');
        }
        return base64Str ? "data:image/svg+xml;base64,".concat(base64Str) : svg;
    }
    return svg;
};
exports.buildBase64UrlFromSvg = buildBase64UrlFromSvg;
