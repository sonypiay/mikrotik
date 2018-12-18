<template lang="html">
<div class="uk-container uk-margin-top">
  <h3>Interface</h3>
  <div class="uk-card uk-card-default uk-card-body uk-card-small">
    <div class="uk-overflow-auto uk-height-large">
      <table class="uk-table uk-table-middle uk-table-hover uk-table-striped uk-table-small uk-table-condensed">
        <thead>
          <tr>
            <th>Name</th>
            <th>Mac Address</th>
            <th>Rx</th>
            <th>Tx</th>
            <th>Disabled</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="iface in interfaces">
            <td>{{ iface.name }}</td>
            <td>{{ iface["mac-address"] }}</td>
            <td>{{ formatSize( iface["rx-packet"] ) }}</td>
            <td>{{ formatSize( iface["tx-packet"] ) }}</td>
            <td>
              <span v-if="iface.disabled === 'false'">No</span>
              <span v-else>Yes</span>
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
      interfaces: []
    }
  },
  methods: {
    showInterface() {
      axios({
        method: 'get',
        url: this.url + '/api/mikrotik/interface/' + this.device.device_id
      }).then(res => {
        let result = res.data;
        this.interfaces = result.result;
        console.log( this.interfaces );
      }).catch(err => {
        console.log(err.response.status);
      });
    },
    formatSize: function(size) {
      var sizes = ['bytes', 'KB', 'MB', 'GB', 'TB'];
      var result;
      if( isNaN(size) ) {
        result = 'NaN';
      }
      else {
        if (size == 0) return '0 Byte';
        var i = parseInt(Math.floor(Math.log(size) / Math.log(1024)));
        result = Math.round(size / Math.pow(1024, i), 2) + ' ' + sizes[i];
      }
      return result;
    }
  },
  mounted() {
    this.showInterface();
  }
}
</script>
