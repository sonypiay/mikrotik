<template lang="html">
  <div>

    <div id="modal" uk-modal>
      <div class="uk-modal-dialog uk-modal-body">
        <a class="uk-modal-close-default" uk-close></a>
        <h3>
          <span v-if="forms.edit">Edit Walled Garden</span>
          <span v-else>Add Walled Garden</span>
        </h3>
        <form class="uk-form-stacked" @submit.prevent="addOrUpdateWalledGarden">
          <div v-if="errorMessage" class="uk-alert-danger" uk-alert>{{ errorMessage }}</div>
          <!--<div class="uk-margin">
            <label class="uk-form-label">Server</label>
            <div class="uk-form-controls">
              <select class="uk-select" v-model="forms.server">
                <option value="">-- Server --</option>
                <option v-for="hs in hotspotserver" :value="hs.name">{{ hs.name }}</option>
              </select>
            </div>
          </div>-->
          <div class="uk-margin">
            <label class="uk-form-label">Dst. Host</label>
            <div class="uk-form-controls">
              <input type="text" class="uk-input" v-model="forms.dsthost">
            </div>
          </div>
          <div class="uk-margin">
            <button class="uk-button uk-button-primary" v-html="forms.submit"></button>
            <a class="uk-button uk-button-default uk-modal-close">Close</a>
          </div>
        </form>
      </div>
    </div>

    <a class="uk-margin-bottom uk-button uk-button-text uk-button-small" :href="url + '/location/' + device.region_domain_id"><span uk-icon="chevron-left"></span> Back</a>
    <h3>Walled Garden - {{ device.device_name }}</h3>
    <a @click="showModal()" class="uk-button uk-button-default">Add Walled Garden</a>
    <div class="uk-overflow-auto uk-margin-top">
      <table class="uk-table uk-table-middle uk-table-hover uk-table-striped uk-table-small uk-table-condensed">
        <thead>
          <tr>
            <th>#</th>
            <th>Server</th>
            <th>Dst Host</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="wd in walled_garden" v-if="wd['dst-host'] !== undefined">
            <td>
              <a class="uk-button uk-button-text" @click="showModal(wd)" uk-tooltip="Edit"><span uk-icon="pencil"></span></a>
              <a class="uk-button uk-button-text" @click="deleteWalledGarden(wd)" uk-tooltip="Delete"><span uk-icon="trash"></span></a>
            </td>
            <td>{{ wd.server }}</td>
            <td>{{ wd['dst-host'] }}</td>
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
      forms: {
        id: '',
        dsthost: '',
        error: false,
        submit: 'Add'
      },
      errors: {},
      errorMessage: '',
      walled_garden: [],
      hotspotserver: []
    }
  },
  methods: {
    getWalledGarden()
    {
      axios({
        method: 'get',
        url: this.url + '/api/mikrotik/walled_garden/' + this.device.device_id
      }).then( res => {
        let result = res.data;
        this.walled_garden = result.result;
      }).catch( err => {
        console.log( err.response.statusText );
      });
    },
    getHotspotServer()
    {
      axios({
        method: 'get',
        url: this.url + '/api/mikrotik/hotspot_server/' + this.device.device_id
      }).then(res => {
        let result = res.data;
        this.hotspotserver = result.result;
      }).catch(err => {
        console.log(err.response.status);
      });
    },
    showModal(walled) {
      if( walled === undefined )
      {
        this.forms.id = '';
        this.forms.dsthost = '';
        this.forms.edit = false;
        this.forms.submit = 'Add';
      }
      else
      {
        this.forms.id = walled['.id'];
        this.forms.dsthost = walled['dst-host'];
        this.forms.edit = true;
        this.forms.submit = 'Edit';
      }
      this.errors = {};
      this.errorMessage = '';
      UIkit.modal('#modal').show();
    },
    addOrUpdateWalledGarden()
    {
      this.errors = {};
      this.errorMessage = '';

      var url, method, param = {};
      if( this.forms.dstaddress === undefined )
      {
        this.forms.dstaddress = '';
      }

      if( this.forms.dsthost === undefined )
      {
        this.forms.dsthost = '';
      }

      if( this.forms.edit === true )
      {
        url = this.url + '/api/mikrotik/update/walled_garden/' + this.device.device_id;
        method = 'put';
        param = {
          dsthost: this.forms.dsthost,
          id: this.forms.id
        };
      }
      else
      {
        url = this.url + '/api/mikrotik/add/walled_garden/' + this.device.device_id;
        method = 'post';
        param = {
          dsthost: this.forms.dsthost
        };
      }

      this.forms.submit = '<span uk-spinner></span>';
      axios({
        method: method,
        url: url,
        params: param
      }).then( res => {
        swal({
          title: 'Success',
          text: res.data.response,
          icon: 'success',
          timer: 3000
        });
        this.getWalledGarden();
        setTimeout(function(){ UIkit.modal('#modal').hide(); }, 2000);
        this.forms.submit = 'Add';
      }).catch( err => {
        if( err.response.status === 500 )
        {
          this.errorMessage = err.response.data.message;
        }
        else
        {
          this.errorMessage = err.response.data.response;
        }
        this.forms.submit = this.forms.edit === true ? 'Edit' : 'Add';
      });
    },
    deleteWalledGarden(wd)
    {
      swal({
        title: 'Confirmation',
        text: 'Are you sure want delete this walled garden?',
        icon: 'warning',
        dangerMode: true,
        buttons: {
          cancel: true,
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
            url: this.url + '/api/mikrotik/delete/walled_garden/' + this.device.device_id + '?id=' + wd['.id']
          }).then( res => {
            swal({
              title: 'Success',
              text: res.data.response,
              icon: 'success'
            });
            this.getWalledGarden();
          }).catch( err => {
            if( err.response.status === 500 )
            {
              swal({
                title: 'Error',
                text: err.response.data.message,
                icon: 'error',
                dangerMode: true
              });
            }
            else
            {
              swal({
                title: 'Error',
                text: err.response.data.response,
                icon: 'error',
                dangerMode: true
              });
            }
          });
        }
      });
    }
  },
  mounted() { this.getWalledGarden(); this.getHotspotServer(); }
}
</script>
