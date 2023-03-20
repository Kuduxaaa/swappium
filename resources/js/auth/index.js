import axios from 'axios';

class Auth {
    constructor () {
        this.token = window.localStorage.getItem('token');
        let userData = window.localStorage.getItem('user');

        this.user = userData ? JSON.parse(userData) : null;

        if (this.token) {
            axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        }
    }

    login (token, user) {
        window.localStorage.setItem('token', token);
        window.localStorage.setItem('user', JSON.stringify(user));

        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;

        this.user = user;
        this.token = token;
    }

    check () {
        return !! this.token;
    }

    async logout() {
        window.localStorage.removeItem('user');
        window.localStorage.removeItem('token');

        this.token = null;
        this.user = null;

        try
        {
            await axios.post('user/logout');
        }
        catch (error)
        {
            console.log(error);
        }
    }

    getUser () {
        return this.user;
    }

    getToken () {
        return this.token;
    }
}

export default Auth;
