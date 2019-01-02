<template>
<div class="uk-container uk-margin-top">
  <div class=" uk-width-1-3@xl uk-width-1-3@l uk-width-1-2@m uk-width-2-3@s uk-align-center uk-margin-large-top">
    <div class="uk-text-center login-container-logo">
      <img :src="url + '/images/logo/biznet_hotspot.png'" alt="">
    </div>
    <div class="uk-card uk-card-body uk-card-default login-container">
      <div v-if="errorMessage" class="uk-text-small uk-alert-danger" v-html="errorMessage" uk-alert></div>
      <form class="uk-form-stacked" @submit.prevent="doLogin">
        <div class="uk-margin">
          <label class="uk-form-label form-label-login">Username</label>
          <div class="uk-form-controls">
            <input type="text" class="uk-width-1-1 uk-input form-label-input" v-model="forms.username">
          </div>
          <div v-if="errors.username" class="uk-text-small uk-text-danger" v-html="errors.username"></div>
        </div>
        <div class="uk-margin">
          <label class="uk-form-label form-label-login">Password</label>
          <div class="uk-form-controls">
            <input type="password" class="uk-width-1-1 uk-input form-label-input" v-model="forms.password">
          </div>
          <div v-if="errors.password" class="uk-text-small uk-text-danger" v-html="errors.password"></div>
        </div>
        <!--<div class="uk-margin">
          <div class="uk-form-controls">
            <div class="uk-text-center form-rememberme">
              <label> <input type="checkbox" class="uk-checkbox" value="Y"> Remember Me </label>
            </div>
          </div>
        </div>-->
        <div class="uk-margin">
          <button v-html="forms.submit" class="uk-width-1-1 uk-button uk-button-primary form-btnlogin">Sign in</button>
        </div>
      </form>
    </div>
  </div>
</div>
</template>

<script>
export default {
  props: ['url'],
  data() {
    return {
      forms: {
        username: '',
        password: '',
        submit: 'Sign In'
      },
      errors: {},
      errorMessage: ''
    }
  },
  methods: {
    doLogin() {
      this.errors = {};
      this.errorMessage = '';

      if( this.forms.username === '' )
      {
        this.forms.error = true;
        this.errors.username = 'Please enter your username';
      }
      if( this.forms.password === '' )
      {
        this.forms.error = true;
        this.errors.password = 'Please enter your password';
      }

      if( this.forms.error === true )
      {
        this.forms.error = false;
        return false;
      }

      this.forms.submit = '<span uk-spinner></span>';
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

        setTimeout(function () { window.location.href = ''; }, 2000);
      }).catch(err => {
        let status = err.response.status;
        if( status === 401 )
        {
          let message = err.response.data;
          this.errorMessage = message.statusText;
        }
        else
        {
          this.errorMessage = err.response.statusText;
        }
        this.forms.submit = 'Sign In';
      });
    }
  }
}
</script>
