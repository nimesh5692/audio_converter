<template>
    <div class="container">
        <!-- Login -->
        <div class="row">
          <div class="offset-3 col-6">
            <form action="#" @submit.prevent="handleLogin">
              <h1 class="text-center">Login</h1>
              <br />
              <div class="row">
                <input type="email" name="email" id="email" v-model="formData.email" class="form-control form-control-sm rounded-0" placeholder="Email">
              </div>
              <br />
              <div class="row">
                <input type="password" name="password" id="password" v-model="formData.password" class="form-control form-control-sm rounded-0" placeholder="Passsword">
              </div>
              <br />
              <div class="row">
                <input type="submit" class="btn btn-sm btn-primary btn-block rounded-0" value="Sign In">
              </div>
            </form>
            <br />
            <button class="btn btn-block btn-link" @click="register">Register</button>
          </div>
        </div>
    </div>
</template>

<script>

    import api from '../services/api';
    export default {
      data() {
        return {
          formData: {
            email: '',
            password: '',
          }
        }
      },

      methods: {
        handleLogin() {
          api.get('/sanctum/csrf-cookie').then(response => {
            api.post('/login', this.formData).then(() => {
              this.redirectUser();
            });
          });
        },

        redirectUser() {
          // window.history.length > 1 ? this.$router.go(-1) : this.$router.push('/');
          this.$router.push('/home');
        },

        register() {
          this.$router.push('/register');
        }
      },

      mounted() {
        console.log('Login Component')
      }
    }
</script>
