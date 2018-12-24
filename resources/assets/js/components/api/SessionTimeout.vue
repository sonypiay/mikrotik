<template lang="html">
  <div>
    <a class="uk-margin-bottom uk-button uk-button-text uk-button-small" :href="url + '/location/' + device.region_domain_id"><span uk-icon="chevron-left"></span> Back</a>
    <h3>Session Timeout - {{ device.device_name }}</h3>
    <form class="uk-form-stacked" @submit.prevent="updateSessionTimeout">
      <div class="uk-margin">
        <label class="uk-form-label">Session Timeout</label>
        <div class="uk-form-controls">
          <input type="text" class="uk-width-medium uk-input" v-model="forms.session" uk-tooltip title="format: 0h0m0s|00:00:00">
        </div>
      </div>
      <div class="uk-margin">
        <label class="uk-form-label">Keepalive Timeout</label>
        <div class="uk-form-controls">
          <input type="text" class="uk-width-medium uk-input" v-model="forms.keepalive" uk-tooltip title="format: 0h0m0s|00:00:00">
        </div>
      </div>
      <div class="uk-margin">
        <button v-html="forms.submit" class="uk-button uk-button-primary"></button>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  props: ['url', 'device'],
  data() {
    return {
      forms: {
        id: '',
        session: 0,
        keepalive: 0,
        submit: 'Edit'
      }
    }
  },
  methods: {
    getSessionTimeout()
    {
      axios({
        method: 'get',
        url: this.url + '/api/mikrotik/sessiontimeout/' + this.device.device_id
      }).then( res => {
        let result = res.data;
        this.forms.id = result.result[0]['.id'];
        this.forms.session = result.result[0]['session-timeout'];
        this.forms.keepalive = result.result[0]['keepalive-timeout'];
      }).catch( err => {
        console.log( err.response.statusText );
      });
    },
    updateSessionTimeout()
    {
      this.forms.submit = '<span uk-spinner></span>';
      axios({
        method: 'put',
        url: this.url + '/api/mikrotik/update/sessiontimeout/' + this.device.device_id,
        params: {
          id: this.forms.id,
          sessiontimeout: this.forms.session,
          keepalivetimeout: this.forms.keepalive
        }
      }).then( res => {
        swal({
          title: 'Success',
          text: res.data.response,
          icon: 'success'
        });
        this.forms.submit = 'Edit';
        this.getSessionTimeout();
      }).catch( err => {
        if( err.response.status === 500 )
        {
          swal({
            title: 'Error',
            text: err.response.data.message,
            icon: 'error',
            dangerMode: true
          });
        }
        else
        {
          swal({
            title: 'Error',
            text: err.response.data.response,
            icon: 'error',
            dangerMode: true
          });
        }
        this.forms.submit = 'Edit';
      });
    }
  },
  mounted() { this.getSessionTimeout(); }
}
</script>
