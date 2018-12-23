<template lang="html">
  <div>
    <h3>Bandwidth</h3>
    <form class="uk-form-stacked" @submit.prevent="updateBandwidth">
      <div class="uk-margin">
        <label class="uk-form-label">Upload</label>
        <div class="uk-form-controls">
          <input type="text" class="uk-width-medium uk-input" v-model="forms.upload">
        </div>
      </div>
      <div class="uk-margin">
        <label class="uk-form-label">Download</label>
        <div class="uk-form-controls">
          <input type="text" class="uk-width-medium uk-input" v-model="forms.download">
        </div>
      </div>
      <div class="uk-margin">
        <button v-html="forms.submit" class="uk-button uk-button-default"></button>
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
        upload: 0,
        download: 0,
        submit: 'Edit'
      },
      bandwidth: {
        upload: [],
        download: []
      }
    }
  },
  methods: {
    getBandwidth()
    {
      axios({
        method: 'get',
        url: this.url + '/api/mikrotik/bandwidth/' + this.device.device_id
      }).then( res => {
        let result = res.data;
        this.bandwidth.upload = result.result.upload[0];
        this.bandwidth.download = result.result.download[0];
        this.forms.upload = this.bandwidth.upload['pcq-rate'];
        this.forms.download = this.bandwidth.download['pcq-rate'];
      }).catch( err => {
        console.log( err.response.statusText );
      });
    },
    formatSize(size) {
      var sizes = ['B', 'K', 'M', 'G', 'T'];
      var result;
      if( isNaN(size) ) {
        result = 'NaN';
      }
      else {
        if (size == 0) return '0 Byte';
        var i = parseInt(Math.floor(Math.log(size) / Math.log(1024)));
        result = Math.round(size / Math.pow(1024, i), 2) + sizes[i];
      }
      return result;
    },
    updateBandwidth()
    {
      this.forms.submit = '<span uk-spinner></span>';
      axios({
        method: 'put',
        url: this.url + '/api/mikrotik/update/bandwidth/' + this.device.device_id,
        params: {
          upload: this.forms.upload,
          download: this.forms.download,
          id_upload: this.bandwidth.upload['.id'],
          id_download: this.bandwidth.download['.id']
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
        this.getBandwidth();
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
  mounted() { this.getBandwidth(); }
}
</script>
