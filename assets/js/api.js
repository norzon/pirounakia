class API {

    constructor (domain) {
        this.domain = domain;
    }

    param (obj) {
        if (typeof jQuery === "function") {
            return jQuery.param(obj);
        }
        return this.serialize(obj);
    }

    serialize (obj, prefix) {
        let str = [], p;
        for(p in obj) {
            if (obj.hasOwnProperty(p)) {
                let k = prefix ? prefix + "[" + p + "]" : p, v = obj[p];
                str.push((v !== null && typeof v === "object") ?
                this.serialize(v, k) :
                encodeURIComponent(k) + "=" + encodeURIComponent(v));
            }
        }
        return str.join("&");
    }

    ajax (obj) {
        obj = obj || {};
        obj.url = obj.url || "";
        obj.method = obj.method || "GET";
        obj.body = obj.body || {};
        obj.headers = obj.headers || {};
        
        if (!obj.headers['X-Request-With']) {
            obj.headers['X-Request-With'] = 'XMLHttpRequest';
        }
        
        let url = this.domain + obj.url;
        if (obj.method.toLowerCase() === "get") {
            url += this.param(obj.body);
        }
        let request = new Request(url, {
            method: obj.method,
            headers: obj.headers,
            body: obj.method.toLowerCase() !== "get" ? obj.body : undefined
        });
        return fetch(request);
    }
    
    login (params) {
        return this.ajax({
            url: '/login',
            method: 'POST',
            body: params
        });
    }
    
    register (params) {
        return this.ajax({
            url: '/register',
            method: 'POST',
            body: params
        });
    }
}