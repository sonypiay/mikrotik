<template lang="html">
  <div class="uk-card uk-card-body">
    <div class="uk-grid-medium" uk-grid>
      <div class="uk-width-expand">
        <div class="uk-card-title">Account</div>
        <form class="uk-form-stacked uk-margin-top" @submit.prevent="saveProfileInfo">
          <div class="uk-margin">
            <label class="uk-form-label">Username</label>
            <div class="uk-form-controls">
              <input type="text" class="uk-input" v-model="forms.username">
            </div>
            <div v-if="errors.username" class="uk-text-small uk-text-danger" v-html="errors.username"></div>
          </div>
          <div class="uk-margin">
            <label class="uk-form-label">Fullname</label>
            <div class="uk-form-controls">
              <input type="text" class="uk-input" v-model="forms.fullname">
            </div>
            <div v-if="errors.fullname" class="uk-text-small uk-text-danger" v-html="errors.fullname"></div>
          </div>
          <div class="uk-margin">
            <label class="uk-form-label">Password</label>
            <div class="uk-form-controls">
              <input type="text" class="uk-input" v-model="forms.password">
            </div>
            <div v-if="errors.password" class="uk-text-small uk-text-danger" v-html="errors.password"></div>
          </div>
          <div class="uk-margin">
            <button class="uk-button uk-button-primary" v-html="forms.submit"></button>
          </div>
        </form>
      </div>
      <div class="uk-width-1-2@xl uk-width-1-2@l uk-width-1-4@m uk-width-1-2@s">
        <div class="uk-card-title">Profile Picture</div>
        <div class="uk-margin-top" uk-form-custom="target: true">
          <div class="uk-margin">
            <label class="uk-form-label">Profile picture</label>
            <div class="uk-form-controls">
              <div class="uk-width-1-1" uk-form-custom="target: true">
                <input type="file" @change="getEventPicture">
                <input class="uk-input uk-width-1-1" type="text" placeholder="JPG/PNG" disabled>
                <span class="uk-text-small uk-text-muted">Max: 2MB</span>
              </div>
              <button @click="saveProfilePicture()" class="uk-button uk-button-primary uk-margin-top" v-html="forms.submitPicture"></button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['url', 'users'],
  data() {
    return {
      forms: {
        username: this.users.username,
        fullname: this.users.fullname,
        password: '',
        picture: '',
        error: false,
        submit: 'Save',
        submitPicture: 'Save Picture'
      },
      errors: {},
      errorMessage: ''
    }
  },
  methods: {
    saveProfileInfo()
    {
      this.errors = {};
      this.errorMessage = '';

      if( this.forms.username === '' )
      {
        this.errors.username = 'Please fill your username';
        this.forms.error = true;
      }

      if( this.forms.fullname === '' )
      {
        this.errors.fullname = 'Please fill your Fullname';
        this.forms.error = true;
      }

      if( this.forms.error === true )
      {
        this.forms.error = false;
        return false;
      }

      if( this.forms.password !== '' )
      {
        if( this.forms.password.length < 8 )
        {
          this.errors.password = 'Password must be at least 8 characters.';
          return false;
        }
      }

      this.forms.submit = '<span uk-spinner></span>';
      axios({
        method: 'put',
        url: this.url + '/users/saveprofile',
        params: {
          username: this.forms.username,
          password: this.forms.password,
          fullname: this.forms.fullname
        }
      }).then( res => {
        swal({
          title: 'Success',
          text: res.data.statusText,
          icon: 'success',
          timer: 3000
        });
        setTimeout(function(){
          document.location = '';
        }, 2000);
      }).catch( err => {
        if( err.response.status === 500 )
        {
          this.errorMessage = err.response.statusText;
        }
        else
        {
          this.errorMessage = err.response.data.statusText;
        }
        this.forms.submit = 'Save';
      });
    },
    getEventPicture: function(event)
    {
      this.forms.picture = event.target.files[0];
    },
    getFormatFile(files) {
      var length_str_file = files.length;
      var getIndex = files.lastIndexOf(".");
      var getformatfile = files.substring( length_str_file, getIndex + 1 ).toLowerCase();
      return getformatfile;
    },
    saveProfilePicture()
    {
      if( this.forms.picture === '' )
      {
        swal({
          title: 'Warning',
          text: 'Please insert a picture',
          icon: 'warning',
          dangerMode: true,
          timer: 3000
        });
      }
      else
      {
        var formatfile = this.getFormatFile( this.forms.picture.name );
        if( formatfile === 'jpg' || formatfile === 'png' || formatfile === 'jpeg' )
        {
          var formdata = new FormData();
          if( this.forms.picture.size > 2048000 )
          {
            swal({
              title: 'Warning',
              text: 'Image file too large',
              icon: 'warning',
              dangerMode: true,
              timer: 3000
            });
          }
          else
          {
            formdata.append('picture', this.forms.picture);
            axios.post( this.url + '/users/savepicture', formdata ).then( res => {
              swal({
                title: 'Success',
                text: res.data.statusText,
                icon: 'success',
                timer: 3000
              });
              setTimeout(function() {
                document.location = '';
              }, 3000);
            }).catch( err => {
              swal({
                title: 'Warning',
                text: err.response.statusText,
                icon: 'warning',
                dangerMode: true,
                timer: 3000
              });
            });
          }
        }
        else
        {
          swal({
            title: 'Warning',
            text: 'Image file must be JPG / PNG',
            icon: 'warning',
            dangerMode: true,
            timer: 3000
          });
        }
      }
    }
  },
  mounted() {}
}
</script>
