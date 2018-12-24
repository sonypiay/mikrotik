<template lang="html">
<div>
  <a class="uk-margin-bottom uk-button uk-button-text uk-button-small" :href="url + '/location/' + device.region_domain_id"><span uk-icon="chevron-left"></span> Back</a>
  <div v-if="errors.error" class="uk-margin-bottom uk-margin-top uk-alert-danger" uk-alert>{{ errors.error }}</div>
  <div class="uk-grid-small" uk-grid>
    <div class="uk-width-1-1">
      <div class="uk-card uk-card-body uk-card-small uk-card-default box_deviceinfo">
        <div class="device_heading">IP Address</div>
        <div class="device_value">{{ device.device_ip }}</div>
      </div>
    </div>
    <div class="uk-width-1-2@xl uk-width-1-2@l uk-width-1-2@m uk-width-1-1@s">
      <div class="uk-card uk-card-body uk-card-small uk-card-default box_deviceinfo">
        <div class="device_heading">Region</div>
        <div class="device_value">{{ device.region_domain_name }}</div>
      </div>
    </div>
    <div class="uk-width-1-2@xl uk-width-1-2@l uk-width-1-2@m uk-width-1-1@s">
      <div class="uk-card uk-card-body uk-card-small uk-card-default box_deviceinfo">
        <div class="device_heading">Location</div>
        <div class="device_value">{{ device.device_name }}</div>
      </div>
    </div>
    <!--<div class="uk-width-1-1">
      <div class="uk-card uk-card-body uk-card-small uk-card-default box_deviceinfo">
        <div class="device_heading">Description</div>
        <div class="uk-text-small uk-text-muted" v-html="device.device_description"></div>
      </div>
    </div>-->
    <div class="uk-width-1-3@xl uk-width-1-3@l uk-width-1-2@m uk-width-1-2@s">
      <div class="uk-card uk-card-body uk-card-small uk-card-default box_deviceinfo">
        <div class="device_heading">Mikrotik</div>
        <div class="device_value">{{ device.device_type }}</div>
      </div>
    </div>
    <div class="uk-width-1-3@xl uk-width-1-3@l uk-width-1-2@m uk-width-1-2@s">
      <div class="uk-card uk-card-body uk-card-small uk-card-default box_deviceinfo">
        <div class="device_heading">Status</div>
        <div class="device_value">
          <span v-if="results.statusdevice.response === 'Connected'" class="uk-text-success">Connected</span>
          <span v-else class="uk-text-danger">Disconnected</span>
        </div>
      </div>
    </div>
    <div class="uk-width-1-3@xl uk-width-1-3@l uk-width-1-2@m uk-width-1-2@s">
      <div class="uk-card uk-card-body uk-card-small uk-card-default box_deviceinfo">
        <div class="device_heading">Uptime</div>
        <div class="device_value">{{ results.resource.uptime }}</div>
      </div>
    </div>
    <div class="uk-width-1-3@xl uk-width-1-3@l uk-width-1-2@m uk-width-1-2@s">
      <div class="uk-card uk-card-body uk-card-small uk-card-default box_deviceinfo">
        <div class="device_heading">CPU</div>
        <div class="device_value">{{ '%' + results.resource["cpu-load"] }}</div>
      </div>
    </div>
    <div class="uk-width-1-3@xl uk-width-1-3@l uk-width-1-2@m uk-width-1-2@s">
      <div class="uk-card uk-card-body uk-card-small uk-card-default box_deviceinfo">
        <div class="device_heading">Memory</div>
        <div class="device_value">{{ formatSize( results.resource["free-memory"] ) }} of {{ formatSize( results.resource["total-memory"] ) }}</div>
      </div>
    </div>
    <div class="uk-width-1-3@xl uk-width-1-3@l uk-width-1-2@m uk-width-1-2@s">
      <div class="uk-card uk-card-body uk-card-small uk-card-default box_deviceinfo">
        <div class="device_heading">Firmware</div>
        <div class="device_value">{{ results.resource.version }}</div>
      </div>
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
        resource: {},
        statusdevice: {}
      },
      errors: {}
    }
  },
  methods: {
    devicesInfo() {
      this.errors = {};
      axios({
        method: 'get',
        url: this.url + '/api/mikrotik/resource/' + this.device.device_id
      }).then( res => {
        let result = res.data.result;
        this.results.resource = result.resource.result[0];
        this.results.statusdevice = result.statusDevice
        console.log(this.results.resource);
      }).catch( err => {
        this.errors.error = err.response.statusText;
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
        result = Math.round(size / Math.pow(1024, i), 2) + '' + sizes[i];
      }
      return result;
    }
  },
  mounted() { this.devicesInfo(); }
}
</script>
