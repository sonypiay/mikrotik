<template lang="html">
<div>
  <div id="addOrUpdate" uk-modal>
    <div class="uk-modal-dialog">
      <div class="uk-modal-body">
        <h3>
          <span v-if="forms.edit">Update Device</span>
          <span v-else>Add Device</span>
        </h3>
        <form class="uk-form-stacked" @submit.prevent="addOrUpdateDevices">
          <div class="uk-margin">
            <label class="uk-form-label">Name</label>
            <div class="uk-form-controls">
              <input type="text" class="uk-input" placeholder="Device name" v-model="forms.name">
            </div>
          </div>
          <div class="uk-margin">
            <label class="uk-form-label">IP</label>
            <div class="uk-form-controls">
              <input type="text" class="uk-input" placeholder="Device IP" v-model="forms.ip">
            </div>
          </div>
          <div class="uk-margin">
            <label class="uk-form-label">Type</label>
            <div class="uk-form-controls">
              <input type="text" class="uk-input" placeholder="Device type" v-model="forms.type">
            </div>
          </div>
          <div class="uk-margin">
            <label class="uk-form-label">Zone Domain</label>
            <div class="uk-form-controls">
              <select class="uk-select" v-model="forms.region">
                <option :disabled="true" value="" selected>-- Select Zone --</option>
                <option v-for="reg in region" :value="reg.region_domain_id" v-text="reg.region_domain_name"></option>
              </select>
            </div>
          </div>
          <div class="uk-margin">
            <label class="uk-form-label">Username Mikrotik</label>
            <div class="uk-form-controls">
              <input type="text" v-model="forms.username_mikrotik" placeholder="Username" class="uk-width-1-1 uk-input">
            </div>
          </div>
          <div class="uk-margin">
            <label class="uk-form-label">Password Mikrotik</label>
            <div class="uk-form-controls">
              <input type="text" v-model="forms.password_mikrotik" placeholder="Password" class="uk-width-1-1 uk-input">
            </div>
          </div>
          <div class="uk-margin">
            <label class="uk-form-label">Port API</label>
            <div class="uk-form-controls">
              <input type="text" v-model="forms.portapi" class="uk-width-1-1 uk-input" placeholder="Port API">
            </div>
          </div>
          <div class="uk-margin">
            <label class="uk-form-label">Description</label>
            <div class="uk-form-controls">
              <textarea class="uk-textarea" v-model="forms.description"></textarea>
            </div>
          </div>
          <div class="uk-margin">
            <button class="uk-button uk-button-primary">
              <span v-if="forms.edit">Save</span>
              <span v-else>Add</span>
            </button>
            <a class="uk-button uk-button-default uk-modal-close">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <header class="uk-navbar uk-box-shadow-small navbarsearch">
    <div class="uk-width-1-1 uk-navbar-item">
      <div class="uk-width-1-1 uk-inline">
        <a @click="listDevices( pagination.path + '?page=' + pagination.current )" class="uk-form-icon" uk-icon="search"></a>
        <input @keyup.enter="listDevices( pagination.path + '?page=' + pagination.current )" type="search" class="uk-width-1-1 uk-input navbarformsearch" v-model="keywords" placeholder="Search...">
      </div>
    </div>
  </header>

  <div class="uk-card uk-card-body">
    <h3>Devices</h3>
    <div class="uk-grid-small" uk-grid>
      <div class="uk-width-1-4@xl uk-width-1-4@l uk-width-1-3@m uk-width-1-1@s">
        <select class="uk-select" v-model="selectedRows" @change="listDevices( pagination.path + '?page=' + pagination.current )">
          <option value="10">10 rows</option>
          <option value="20">20 rows</option>
          <option value="30">30 rows</option>
          <option value="50">50 rows</option>
          <option value="100">100 rows</option>
          <option value="150">150 rows</option>
        </select>
      </div>
      <div class="uk-width-1-4@xl uk-width-1-4@l uk-width-1-3@m uk-width-1-1@s">
        <select class="uk-select" v-model="selectedZone" @change="listDevices( pagination.path + '?page=' + pagination.current )">
          <option value="all" selected>All Domain</option>
          <option v-for="reg in region" :value="reg.region_domain_id">{{ reg.region_domain_name }}</option>
        </select>
      </div>
      <div class="uk-width-1-4@xl uk-width-1-4@l uk-width-1-3@m uk-width-1-1@s">
        <a class="uk-button uk-button-default" @click="addDevices()">Add New Device</a>
      </div>
    </div>
    <div class="uk-overflow-auto uk-margin-top">
      <table class="uk-table uk-table-middle uk-table-hover uk-table-striped uk-table-small uk-table-condensed">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Zone</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(device, index) in devices.results">
            <td>
              <a @click="updateDevices(device)" class="uk-button uk-button-text" uk-tooltip title="Edit" uk-icon="pencil"></a>
              <a @click="deleteDevices(device.device_id)" class="uk-button uk-button-text" uk-tooltip title="Delete" uk-icon="trash"></a>
            </td>
            <td>{{ device.device_name }}</td>
            <td>{{ device.region_domain_name }}</td>
            <!--<td>
              <div v-for="status in devices.statusdevice">
                <span v-if="status.ip === device.device_ip">
                  {{ status.response }}
                </span>
              </div>
            </td>-->
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
  props: ['url', 'region', 'usermikrotik'],
  data() {
    return {
      forms: {
        id: 0,
        name: '',
        ip: '',
        description: '',
        type: '',
        region: '',
        username_mikrotik: '',
        password_mikrotik: '',
        portapi: '',
        edit: false
      },
      keywords: '',
      selectedRows: 10,
      selectedZone: 'all',
      pagination: {
        current: 1,
        next: '',
        prev: '',
        last: '',
        path: this.url + '/devices'
      },
      devices: {
        total: 0,
        results: []
      },
      statusdevice: {}
    }
  },
  methods: {
    addDevices() {
      this.forms = {
        id: 0,
        name: '',
        ip: '',
        description: '',
        type: '',
        region: '',
        username_mikrotik: '',
        password_mikrotik: '',
        portapi: '',
        edit: false
      };
      UIkit.modal('#addOrUpdate').show();
    },
    updateDevices(devices)
    {
      this.forms = {
        id: devices.device_id,
        name: devices.device_name,
        ip: devices.device_ip,
        description: devices.device_description,
        type: devices.device_type,
        region: devices.region_domain_id,
        username_mikrotik: devices.device_username,
        password_mikrotik: devices.device_password,
        portapi: devices.device_port_api,
        edit: true
      };
      UIkit.modal('#addOrUpdate').show();
    },
    deleteDevices(id)
    {
      swal({
        title: 'Confirm',
        text: 'Are you sure?',
        icon: 'warning',
        dangerMode: true,
        buttons: {
          cancel: true,
          confirm: {
            text: 'Delete',
            value: true
          }
        }
      }).then(value => {
        if( value )
        {
          axios({
            method: 'delete',
            url: this.url + '/devices/delete/' + id
          }).then(res => {
            swal({
              title: 'Success',
              text: res.data.statusText,
              icon: 'success'
            });
            this.listDevices();
          }).catch(err => {
            swal({
              title: 'Error',
              text: 'Whoops, something when wrong',
              icon: 'error',
              dangerMode: true
            });
          });
        }
      });
    },
    addOrUpdateDevices()
    {
      if( this.forms.name === '' )
      {
        swal({
          title: 'Warning',
          text: 'Please fill the device name',
          icon: 'warning',
          dangerMode: true
        });
      }
      else if( this.forms.ip === '' )
      {
        swal({
          title: 'Warning',
          text: 'Please fill the device ip',
          icon: 'warning',
          dangerMode: true
        });
      }
      else
      {
        var method, url = '';
        if( this.forms.edit === false )
        {
          method = 'post';
          url = this.url + '/devices/add';
        }
        else
        {
          method = 'put';
          url = this.url + '/devices/update/' + this.forms.id;
        }

        axios({
          method: method,
          url: url,
          params: {
            device_ip: this.forms.ip,
            device_name: this.forms.name,
            device_desc: this.forms.description,
            device_type: this.forms.type,
            username_mikrotik: this.forms.username_mikrotik,
            password_mikrotik: this.forms.password_mikrotik,
            portapi: this.forms.portapi,
            region: this.forms.region
          }
        }).then( res => {
          swal({
            title: 'Success',
            text: res.data.statusText,
            icon: 'success'
          });
          if( this.forms.edit === false )
          {
            this.forms = {
              id: 0,
              name: '',
              ip: '',
              description: '',
              type: '',
              region: '',
              username_mikrotik: '',
              password_mikrotik: '',
              portapi: '',
              edit: false
            };
          }
          setTimeout(function() { UIkit.modal('#addOrUpdate').hide(); }, 2000);
          this.listDevices();
        }).catch( err => {
          let status = err.response.status;
          if( status === 409 )
          {
            swal({
              title: 'Warning',
              text: err.response.data.statusText,
              icon: 'warning',
              dangerMode: true
            });
          }
          else
          {
            swal({
              title: 'Warning',
              text: err.response.statusText,
              icon: 'warning',
              dangerMode: true
            });
          }
        });
      }
    },
    listDevices(pages) {
      var param = '&limit=' + this.selectedRows + '&keywords=' + this.keywords + '&zone=' + this.selectedZone;

      if( pages === undefined )
        pages = this.url + '/devices/listdevice?page=' + this.pagination.current + param;
      else
        pages = pages + param;

      axios({
        method: 'get',
        url: pages
      }).then( res => {
        let result = res.data;
        this.devices = {
          total: result.result.total,
          results: result.result.data
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
