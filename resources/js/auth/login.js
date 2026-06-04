import api from '../api/axios';

window.login = async function(){

    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    try{

        const response = await api.post('/admin/auth/login', {

            email: email,
            password: password

        });

        console.log(response.data);

        // simpan token
        localStorage.setItem(
            'token',
            response.data.token
        );

        alert('Login berhasil');

        window.location.href = '/home';

    }catch(error){

        console.log(error);

        alert('Email atau password salah');

    }

}