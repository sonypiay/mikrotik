<template>
<div class="uk-container uk-margin-large-top">
  <div class=" uk-width-1-3@xl uk-width-1-3@l uk-width-1-2@m uk-width-2-3@s uk-align-center uk-margin-large-top">
    <div class="uk-card uk-card-body uk-card-default login-container">
      <div class="uk-card-title uk-text-center">Login Account</div>
      <form class="uk-form-stacked" @submit.prevent="doLogin">
        <div class="uk-margin">
          <label class="uk-form-label">Username</label>
          <div class="uk-form-controls">
            <div class="uk-width-1-1 uk-inline">
              <span class="uk-form-icon" uk-icon="user"></span>
              <input type="text" class="uk-width-1-1 uk-input" placeholder="Enter your username" v-model="forms.username">
            </div>
          </div>
        </div>
        <div class="uk-margin">
          <label class="uk-form-label">Password</label>
          <div class="uk-form-controls">
            <div class="uk-width-1-1 uk-inline">
              <span class="uk-form-icon" uk-icon="lock"></span>
              <input type="password" class="uk-width-1-1 uk-input" placeholder="Enter your password" v-model="forms.password">
            </div>
          </div>
        </div>
        <div class="uk-margin">
          <button id="submitBtn" class="uk-width-1-1 uk-button uk-button-primary">Log in</button>
        </div>
      </form>
    </div>
  </div>
</div>
</template>

<script>
import swal from 'sweetalert';
export default {
  props: ['url'],
  data() {
    return {
      forms: {
        username: '',
        password: ''
      },
      errors: {}
    }
  },
  methods: {
    doLogin() {
      if( this.forms.username === '' )
      {
        swal({
          title: 'Warning',
          text: 'Please enter your username',
          icon: 'warning',
          dangerMode: true
        });
      }
      else if( this.forms.password === '' )
      {
        swal({
          title: 'Warning',
          text: 'Please enter your password',
          icon: 'warning',
          dangerMode: true
        });
      }
      else
      {
        axios({
          method: 'post',
          url: this.url + '/dologin',
          headers: {'Content-Type': 'application/json'},
          params: {
            username: this.forms.username,
            password: this.forms.password
          }
        }).then(res => {
          let result = res.data;
          swal({
            title: 'Login Success',
            text: 'Redirecting...',
            icon: 'success'
          });
          setTimeout(function () { window.location.href = ''; }, 2000);
        }).catch(err => {
          let status = err.response.status;
          if( status === 401 )
          {
            let message = err.response.data;
            swal({
              title: 'Warning',
              text: message.statusText,
              icon: 'warning',
              dangerMode: true
            });
          }
          else
          {
            swal({
              title: 'Error',
              text: 'An error has occured',
              icon: 'error',
              dangerMode: true
            });
          }
        });
      }
    }
  },
  mounted() {
    console.log('Component mounted.')
  }
}
</script>
