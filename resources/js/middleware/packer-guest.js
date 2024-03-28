export default function packerGuest({next, store}) {
    if (window.localStorage.getItem('packer_token') && window.localStorage.getItem('packer_user')) {
        return next({name: 'warehouse'})
    }

    return next();
}
