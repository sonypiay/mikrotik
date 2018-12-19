<template lang="html">
  <div>
    <div id="modal" uk-modal>
      <div class="uk-modal-dialog uk-modal-body">
        <h3>
          <span v-if="forms.edit">Update Graph</span>
          <span v-else>Add New</span>
        </h3>
        <form class="uk-form-stacked" @submit.prevent="addGraph">
          <div v-if="errorMessage" class="uk-text-small uk-alert-danger" uk-alert>{{ errorMessage }}</div>
          <div class="uk-margin">
            <label class="uk-form-label">Interface</label>
            <div class="uk-form-controls">
              <select class="uk-width-1-1 uk-select" v-model="forms.iface">
                <option value="">-- Interface --</option>
                <option v-for="iface in interfaces" :value="iface.name">{{ iface.name }}</option>
              </select>
            </div>
            <div v-if="errors.iface" class="uk-text-small uk-text-danger">{{ errors.iface }}</div>
          </div>
          <div class="uk-margin">
            <label class="uk-form-label">Allow Address</label>
            <div class="uk-form-controls">
              <input type="text" class="uk-input" value="0.0.0.0/0" v-model="forms.allow_address">
            </div>
          </div>
          <div class="uk-margin">
            <label class="uk-form-label">Store on Disk</label>
            <div class="uk-form-controls">
              <select class="uk-select" v-model="forms.storeondisk">
                <option value="yes">Yes</option>
                <option value="no">No</option>
              </select>
            </div>
          </div>
          <div class="uk-margin">
            <button class="uk-button uk-button-primary" v-html="forms.submit"></button>
            <a class="uk-button uk-button-default uk-modal-close">Cancel</a>
          </div>
        </form>
      </div>
    </div>

    <h3>Graph - {{ device.device_name }} <a class="uk-button uk-button-default" @click="addGraphModal()">Add</a></h3>
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
          <div class="uk-card-media-top">
            <div class="uk-cover-container">
              <iframe v-if="services.port === 80" width="500" height="200" :src="'http://' + device.device_ip + '/graphs/iface/' + graph.interface + '/' + forms.filterDay + '.gif'"></iframe>
              <iframe v-else width="500" height="200" :src="'http://' + device.device_ip + ':' + services.port + '/graphs/iface/' + graph.interface + '/' + forms.filterDay + '.gif'"></iframe>
            </div>
          </div>
          <div class="uk-card-body uk-card-small">
            <div class="uk-card-title">{{ graph.interface }}</div>
            <div class="uk-grid-collapse uk-flex uk-flex-center uk-text-center" uk-grid>
              <div class="uk-width-1-2">
                <button @click="addGraphModal( graph )" class="uk-button uk-button-text uk-margin-top" uk-tooltip="Update">
                  <span uk-icon="pencil"></span>
                </button>
              </div>
              <div class="uk-width-1-2">
                <button @click="deleteGraph( graph['.id'], graph.interface )" class="uk-button uk-button-text uk-margin-top" uk-tooltip="Delete">
                  <span uk-icon="trash"></span>
                </button>
              </div>
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
    addGraphModal(graph) {
      if( graph === undefined )
      {
        this.forms.iface = '';
        this.forms.allow_address = '0.0.0.0/0';
        this.forms.storeondisk = 'yes';
        this.forms.submit = 'Add';
        this.forms.edit = false;
      }
      else
      {
        var isStoreOnDisk = 'Y';
        if( graph['store-on-disk'] )
        {
          isStoreOnDisk = 'yes';
        }
        else
        {
          isStoreOnDisk = 'no';
        }

        this.forms.iface = graph.interface,
        this.forms.allow_address = graph['allow-address'],
        this.forms.storeondisk = isStoreOnDisk,
        this.forms.submit = 'Save',
        this.forms.edit = true
      }

      this.forms.error = false;
      this.errors = {};
      this.errorMessage = '';
      UIkit.modal('#modal').show();
    },
    getInterface()
    {
      axios({
        method: 'get',
        url: this.url + '/api/mikrotik/interface/' + this.device.device_id
      }).then(res => {
        let result = res.data;
        this.interfaces = result.result;
      }).catch(err => {
        console.log(err.response.status);
      });
    },
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
    },
    addGraph()
    {
      this.errors = {};
      this.errorMessage = '';
      if( this.forms.iface === '' )
      {
        this.forms.error = true;
        this.errors.iface = 'Please select interface';
      }

      if( this.forms.error === true )
      {
        this.forms.error = false;
        return false;
      }

      if( this.forms.allow_address === '' )
      {
        this.forms.allow_address = '0.0.0.0/0';
      }

      this.forms.submit = '<span uk-spinner></span>';
      axios({
        method: 'post',
        url: this.url + '/api/mikrotik/add/graph/' + this.device.device_id,
        headers: {
          'Content-Type': 'application/json'
        },
        params: {
          iface: this.forms.iface,
          allow_address: this.forms.allow_address,
          storeondisk: this.forms.storeondisk
        }
      }).then( res => {
        let result = res.data;
        swal({
          title: 'Success',
          text: result.response,
          icon: 'success',
          timer: 3000
        });
        this.getGraphInterface();
        setTimeout(function(){
          UIkit.modal('#modal').hide();
        }, 2000);
      }).catch( err => {
        if( err.response.status === 500 )
        {
          this.errorMessage = err.response.statusText;
        }
        else
        {
          this.errorMessage = err.response.data.response;
        }
        this.forms.submit = 'Add';
      });
    },
    deleteGraph(id, iface)
    {
      swal({
        title: 'Confirm Delete',
        text: 'Are you sure want delete ' + iface + '?',
        icon: 'warning',
        dangerMode: true,
        buttons: {
          cancel: 'Cancel',
          confirm: {
            text: 'Delete',
            value: true
          }
        }
      }).then( val => {
        if( val )
        {
          axios({
            method: 'delete',
            url: this.url + '/api/mikrotik/delete/graph/' + this.device.device_id + '?id=' + id + '&iface=' + iface
          }).then( res => {
            let result = res.data;
            swal({
              title: 'Success',
              text: result.response,
              icon: 'success',
              timer: 3000
            });
            this.getGraphInterface();
          }).catch( err => {
            if( err.response.status === 500 )
            {
              this.errorMessage = err.response.statusText;
            }
            else
            {
              this.errorMessage = err.response.data.response;
            }
            this.forms.submit = 'Add';
          });
        }
      });
    }
  },
  mounted() {
    this.getInterface();
    this.getGraphInterface();
    this.getServices();
  }
}
</script>
