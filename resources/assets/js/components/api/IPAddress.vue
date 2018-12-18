<template lang="html">
<div>
  <div v-if="errors.error" class="uk-margin-bottom uk-margin-top uk-alert-danger" uk-alert>{{ errors.error }}</div>
  <div class="uk-card uk-card-default uk-card-body uk-card-small">
    <div class="uk-overflow-auto uk-height-large">
      <table class="uk-table uk-table-middle uk-table-hover uk-table-striped uk-table-small uk-table-condensed">
        <thead>
          <tr>
            <th>IP</th>
            <th>Network</th>
            <th>Interface</th>
            <th>Disabled</th>
            <th>Dynamic</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="ip in results.ipaddress">
            <td>{{ ip.address }}</td>
            <td>{{ ip.network }}</td>
            <td>{{ ip.interface }}</td>
            <td>
              <span v-if="ip.disabled == false">Yes</span>
              <span v-else>No</span>
            </td>
            <td>
              <span v-if="ip.dynamic == false">Yes</span>
              <span v-else>No</span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
</template>
<script>
export default {
  props: ['url', 'device'],
  data() {
    return {
      results: {
        ipaddress: []
      },
      errors: {}
    }
  },
  methods: {
    getIP()
    {
      axios({
        method: 'get',
        url: this.url + '/api/mikrotik/ipaddress/' + this.device.device_id
      }).then( res => {
        let result = res.data;
        this.results.ipaddress = result.data.ipaddress.result;
      }).catch( err => {
        this.errors.error = err.response.statusText;
      })
    }
  },
  mounted() {
    this.getIP();
  }
}
</script>
