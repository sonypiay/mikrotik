<template lang="html">
  <div>
    <a class="uk-margin-bottom uk-button uk-button-text uk-button-small" :href="url + '/location/' + device.region_domain_id"><span uk-icon="chevron-left"></span> Back</a>
    <h3>Log - {{ device.device_name }}</h3>
    <div class="uk-overflow-auto uk-margin-top uk-height-medium">
      <table class="uk-table uk-table-hover uk-table-striped uk-table-middle uk-table-divider uk-table-small">
        <thead>
          <tr>
            <th>Time</th>
            <th>Message</th>
            <th>Log Type</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="log in logs">
            <td>{{ log.time }}</td>
            <td>{{ log.message }}</td>
            <td>{{ log.topics }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
export default {
  props: ['url', 'device'],
  data() {
    return {
      logs: []
    }
  },
  methods: {
    showlog()
    {
      axios({
        method: 'get',
        url: this.url + '/api/mikrotik/log/' + this.device.device_id
      }).then(res => {
        let result = res.data;
        this.logs = result.result;
      }).catch(err => {
        console.log(err.response.statusText);
      });
    }
  },
  mounted() {
    this.showlog();
  }
}
</script>
