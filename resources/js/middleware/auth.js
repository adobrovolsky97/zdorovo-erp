export default function auth({next, store}) {
    if (!localStorage.getItem('user') || !localStorage.getItem('token')) {
        store.commit('clearUser')
        return next({name: 'login'})
    }
    next();
}
