<template lang="html">
  <div>
    <div class="uk-card uk-card-body">
      <h3>Dashboard</h3>
      <div v-if="summaryap.isLoading === true" class="uk-text-center uk-margin-top">
        <span class="uk-margin-small-right" uk-spinner></span> Loading Data ...
      </div>
      <div v-else>
        <div v-if="summaryap.total === 0" class="uk-alert-warning uk-margin-top" uk-alert>
          No data
        </div>
        <div v-else class="uk-overflow-auto uk-margin-top">
          <table class="uk-table uk-table-middle uk-table-hover uk-table-striped uk-table-small uk-table-condensed">
            <thead>
              <tr>
                <th>Location</th>
                <th>Up</th>
                <th>Down</th>
                <th>Access Point</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="aps in summaryap.results">
                <td class="uk-table-expand"><a :href="url + '/location/' + aps.domain_id">{{ aps.domain_name }}</a></td>
                <td><span class="uk-label uk-label-success">{{ aps.connect }}</span></td>
                <td><span class="uk-label uk-label-danger">{{ aps.disconnect }}</span></td>
                <td><span class="uk-label uk-label-primary">{{ aps.total }}</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['url'],
  data() {
    return {
      summaryap: {
        total: 0,
        results: [],
        isLoading: true
      }
    }
  },
  methods: {
    getSummaryAps()
    {
      axios({
        method: 'get',
        url: this.url + '/summary_ap'
      }).then( res => {
        let result = res.data;
        this.summaryap = {
          total: result.total,
          results: result.results,
          isLoading: false
        };
      }).catch( err => {
        console.error( err.response.statusText );
      });
    }
  },
  mounted() {
    this.getSummaryAps();
  }
}
</script>

<style lang="css">
</style>
