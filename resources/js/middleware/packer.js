export default function packer({next, store}) {
    if (!localStorage.getItem('packer_user') || !localStorage.getItem('packer_token')) {
        store.commit('clearPackerUser')
        return next({name: 'packer-login'})
    }
    next();
}
