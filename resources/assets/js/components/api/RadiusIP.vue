<template lang="html">
  <div>
    <a class="uk-margin-bottom uk-button uk-button-text uk-button-small" :href="url + '/location/' + device.region_domain_id"><span uk-icon="chevron-left"></span> Back</a>
    <h3>Hotspot Address - {{ device.device_name }}</h3>
    <form class="uk-form-stacked" @submit.prevent="updateRadiusIp">
      <div class="uk-margin">
        <input type="text" class="uk-width-medium uk-input" v-model="forms.radiusip">
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
        radiusip: '',
        submit: 'Edit'
      },
      locationid: []
    }
  },
  methods: {
    getLocationID()
    {
      axios({
        method: 'get',
        url: this.url + '/api/mikrotik/locationid/' + this.device.device_id
      }).then( res => {
        let result = res.data;
        this.locationid = result.result[0];
        this.forms.id = this.locationid['.id'];
        this.forms.radiusip = this.locationid['hotspot-address'];
      }).catch( err => {
        console.log( err.response.statusText );
      });
    },
    updateRadiusIp()
    {
      this.forms.submit = '<span uk-spinner></span>';
      axios({
        method: 'put',
        url: this.url + '/api/mikrotik/update/radius_ip/' + this.device.device_id,
        params: {
          id: this.forms.id,
          radius_ip: this.forms.radiusip
        }
      }).then( res => {
        let result = res.data;
        swal({
          title: 'Success',
          text: result.response,
          icon: 'success',
          timer: 3000
        });
        this.forms.submit = 'Edit';
        this.getLocationID();
      }).catch( err => {
        if( err.response.status === 500 )
        {
          this.errorMessage = err.response.statusText;
        }
        else
        {
          this.errorMessage = err.response.data.response;
        }
        this.forms.submit = 'Edit';
      });
    }
  },
  mounted() { this.getLocationID(); }
}
</script>
