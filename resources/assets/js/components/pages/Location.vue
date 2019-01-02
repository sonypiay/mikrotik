<template lang="html">
<div>
  <header class="uk-navbar uk-box-shadow-small navbarsearch">
    <div class="uk-width-1-1 uk-navbar-item">
      <div class="uk-width-1-1 uk-inline">
        <a @click="listDevices( pagination.path + '?page=' + pagination.current )" class="uk-form-icon" uk-icon="search"></a>
        <input @keyup.enter="listDevices( pagination.path + '?page=' + pagination.current )" type="search" class="uk-width-1-1 uk-input navbarformsearch" v-model="keywords" placeholder="Search...">
      </div>
    </div>
  </header>

  <div class="uk-card uk-card-body">
    <h3>{{ domain.region_domain_name }}</h3>
    <div class="uk-margin">
      <select class="uk-width-1-4 uk-select" v-model="selectedRows" @change="listDevices( pagination.path + '?page=' + pagination.current )">
        <option value="10">10 rows</option>
        <option value="20">20 rows</option>
        <option value="30">30 rows</option>
        <option value="50">50 rows</option>
        <option value="100">100 rows</option>
        <option value="150">150 rows</option>
      </select>
    </div>
    <div class="uk-overflow-auto uk-margin-top">
      <table class="uk-table uk-table-middle uk-table-hover uk-table-striped uk-table-small uk-table-condensed">
        <thead>
          <tr>
            <th>Location</th>
            <th>Region</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(device, index) in devices.results">
            <td>{{ device.device_name }}</td>
            <td>{{ device.region_domain_name }}</td>
            <td>
              <div v-for="status in devices.statusdevice">
                <div v-if="status.ip === device.device_ip">
                  <span v-if="status.response === 'Connected'" class="uk-label uk-label-success">Up</span>
                  <span v-else class="uk-label uk-label-danger">Down</span>
                </div>
              </div>
            </td>
            <td>
              <a class="uk-button uk-button-text" :href="url + '/devices/monitor/' + device.device_id" uk-tooltip="Monitor"><span uk-icon="info"></span></a>
              <a v-if="session.privilege === 'admin' || session.privilege === 'user'" class="uk-button uk-button-text" :href="url + '/devices/controller/' + device.device_id" uk-icon="cog" uk-tooltip="Controller"></a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <ul class="uk-margin-top uk-margin-bottom uk-pagination">
      <li>
        <span v-if="pagination.prev">
          <a @click="listDevices( pagination.prev )">
            <span uk-pagination-previous></span>
          </a>
        </span>
        <span v-else>
          <a><span uk-pagination-previous></span></a>
        </span>
      </li>
      <li><span>Page {{ pagination.current }} of {{ pagination.last }}</span></li>
      <li>
        <span v-if="pagination.next">
          <a @click="listDevices( pagination.next )">
            <span uk-pagination-next></span>
          </a>
        </span>
        <span v-else>
          <a><span uk-pagination-next></span></a>
        </span>
      </li>
    </ul>
  </div>
</div>
</template>

<script>
export default {
  props: ['url', 'domain', 'session'],
  data() {
    return {
      keywords: '',
      selectedRows: 10,
      pagination: {
        current: 1,
        next: '',
        prev: '',
        last: 1,
        path: this.url + '/selectedlocation'
      },
      devices: {
        total: 0,
        results: [],
        statusdevice: []
      },
      statusdevice: {}
    }
  },
  methods: {
    listDevices(pages) {
      var param = '&limit=' + this.selectedRows + '&keywords=' + this.keywords + '&zone=' + this.domain.region_domain_id;

      if( pages === undefined )
        pages = this.url + '/selectedlocation?page=' + this.pagination.current + param;
      else
        pages = pages + param;

      axios({
        method: 'get',
        url: pages
      }).then( res => {
        let result = res.data;
        this.devices = {
          total: result.result.total,
          results: result.result.data,
          statusdevice: result.statusdevice
        };
        this.pagination = {
          current: result.result.current_page,
          next: result.result.next_page_url,
          prev: result.result.prev_page_url,
          last: result.result.last_page,
          path: result.result.path
        };
      }).catch( err => {
        console.log(err.response.statusText);
      });
    }
  },
  mounted() {
    this.listDevices();
  }
}
</script>
