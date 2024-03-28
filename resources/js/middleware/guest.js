export default function guest({next, store}) {
    if (window.localStorage.getItem('token') && window.localStorage.getItem('user')) {
        return next({name: 'login'})
    }

    return next();
}
