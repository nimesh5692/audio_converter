<template>
    <div class="container">
        <!-- Login -->
        <div class="row">
          <div class="offset-3 col-6">
            <form action="#" @submit.prevent="handleReg">
              <h1 class="text-center">Register</h1>
              <br />
              <div class="row">
                <input type="text" name="name" id="name" v-model="formData.name" class="form-control form-control-sm rounded-0" placeholder="Name">
              </div>
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
                <input type="password" name="password_confirmation" id="password_confirmation" v-model="formData.password_confirmation" class="form-control form-control-sm rounded-0" placeholder="Passsword">
              </div>
              <br />
              <div class="row">
                <input type="submit" class="btn btn-sm btn-primary btn-block rounded-0" value="Register">
              </div>
            </form>
            <br />
            <button class="btn btn-block btn-link">Sign in</button>
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
            name: '',
            email: '',
            password: '',
            password_confirmation: '',
          }
        }
      },

      methods: {
        handleReg() {
          api.post('/register', this.formData).then(() => {
            this.redirectUser();
          });
        },

        redirectUser() {
          // window.history.length > 1 ? this.$router.go(-1) : this.$router.push('/');
          this.$router.push('/login');
        },
      },

      mounted() {
        console.log('Login Component')
      }
    }
</script>
