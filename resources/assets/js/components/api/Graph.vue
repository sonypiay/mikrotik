<template lang="html">
  <div>

    <a class="uk-margin-bottom uk-button uk-button-text uk-button-small" :href="url + '/location/' + device.region_domain_id"><span uk-icon="chevron-left"></span> Back</a>
    <h3>Graph - {{ device.device_name }}</h3>
    <div class="uk-grid-small" uk-grid>
      <div class="uk-width-1-6@xl uk-width-1-6@l uk-width-1-4@m uk-width-1-2@s">
        <select class="uk-select" v-model="forms.filterDay">
          <option value="daily">Daily</option>
          <option value="weekly">Week</option>
          <option value="monthly">Month</option>
          <option value="yearly">Year</option>
        </select>
      </div>
    </div>
    <div class="uk-grid-small uk-margin" uk-grid>
      <div class="uk-width-1-2@xl uk-width-1-2@l uk-width-1-2@m uk-width-1-2@s" v-for="graph in graphing">
        <div class="uk-card uk-card-default">
          <div class="uk-card-body uk-card-small">
            <div class="uk-card-title">{{ graph.interface }}</div>
          </div>
          <div class="uk-card-media-bottom">
            <div class="uk-cover-container">
              <iframe width="500" height="200" :src="url + '/mikrotik/graphImage?ip=' + device.device_ip + '&port=' + services.port + '&iface=' + graph.interface + '&filtertime=' + forms.filterDay"></iframe>
            </div>
          </div>
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
      interfaces: [],
      graphing: [],
      services: {
        port: 80
      },
      ifaces: {},
      forms: {
        iface: '',
        allow_address: '0.0.0.0/0',
        storeondisk: 'yes',
        submit: 'Add',
        filterDay: 'daily',
        error: false,
        edit: false
      },
      errors: {},
      errorMessage: ''
    }
  },
  methods: {
    getGraphInterface()
    {
      axios({
        method: 'get',
        url: this.url + '/api/mikrotik/graph/' + this.device.device_id
      }).then(res => {
        let result = res.data;
        this.graphing = result.result;
      }).catch(err => {
        console.log(err.response.status);
      });
    },
    getServices()
    {
      axios({
        method: 'get',
        url: this.url + '/api/mikrotik/find/service/' + this.device.device_id + '?name=name&value=www'
      }).then(res => {
        let result = res.data;
        this.services = result.result[0];
        console.log( this.services );
      }).catch(err => {
        console.log(err.response.status);
      });
    }
  },
  mounted() {
    this.getGraphInterface();
    this.getServices();
  }
}
</script>
