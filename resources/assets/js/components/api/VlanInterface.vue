<template lang="html">
<div class="uk-container uk-margin-top">
  <h3>Vlan</h3>
  <div id="modal" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
      <a class="uk-modal-close-default" uk-close></a>
      <h3>
        <span v-if="forms.edit">Edit Vlan</span>
        <span v-else>Tambah Vlan</span>
      </h3>
      <div v-if="errorMessage" class="uk-alert-danger" uk-alert>{{ errorMessage }}</div>
      <form class="uk-form-stacked" @submit.prevent="addOrUpdateVlan">
        <div class="uk-margin">
          <label class="uk-form-label">Name</label>
          <div class="uk-form-controls">
            <input type="text" class="uk-width-1-1 uk-input" v-model="forms.name" placeholder="Ex: vlan4-management">
          </div>
          <div v-if="errors.name" class="uk-text-small uk-text-danger">{{ errors.name }}</div>
        </div>
        <div class="uk-margin">
          <label class="uk-form-label">Vlan ID</label>
          <div class="uk-form-controls">
            <input type="text" class="uk-width-1-1 uk-input" v-model="forms.vlanid" placeholder="Ex: 4">
          </div>
          <div v-if="errors.vlanid" class="uk-text-small uk-text-danger">{{ errors.vlanid }}</div>
        </div>
        <div class="uk-margin">
          <label class="uk-form-label">Interface</label>
          <div class="uk-form-controls">
            <select class="uk-width-1-1 uk-select" v-model="forms.interfaces">
              <option value="" selected>-- interface --</option>
              <option v-for="intface in interfaces" :value="intface.name">{{ intface.name }}</option>
            </select>
            <div v-if="errors.interfaces" class="uk-text-small uk-text-danger">{{ errors.interfaces }}</div>
          </div>
        </div>
        <div class="uk-margin">
          <label class="uk-form-label">ARP</label>
          <div class="uk-form-controls">
            <select class="uk-width-1-1 uk-select" v-model="forms.arp">
              <option value="enabled">Enabled</option>
              <option value="disabled">Disabled</option>
            </select>
          </div>
        </div>
        <div class="uk-margin">
          <label class="uk-form-label">Disabled</label>
          <div class="uk-form-controls">
            <select class="uk-width-1-1 uk-select" v-model="forms.disabled">
              <option value="false">No</option>
              <option value="true">Yes</option>
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
  <div class="uk-card uk-card-default uk-card-body uk-card-small">
    <button class="uk-button uk-button-default" @click="addOrUpdateModal()">Add</button>
    <div class="uk-overflow-auto uk-height-large">
      <table class="uk-table uk-table-middle uk-table-hover uk-table-striped uk-table-small uk-table-condensed">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Mac Address</th>
            <th>Vlan ID</th>
            <th>Interface</th>
            <th>ARP</th>
            <th>Disabled</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="iface in interfacevlan">
            <td>
              <a @click="addOrUpdateModal(iface)" class="uk-button uk-button-text" uk-tooltip="title: Edit"><span uk-icon="pencil"></span></a>
              <a @click="deleteVlan(iface['.id'], iface.name)" class="uk-button uk-button-text" uk-tooltip="title: Remove"><span uk-icon="trash"></span></a>
            </td>
            <td>{{ iface.name }}</td>
            <td>{{ iface["mac-address"] }}</td>
            <td>{{ iface["vlan-id"] }}</td>
            <td>{{ iface.interface }}</td>
            <td>
              <span v-if="iface.arp === 'disabled'">Disabled</span>
              <span v-else>Enabled</span>
            </td>
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
      forms: {
        id: '',
        name: '',
        vlanid: 1,
        disabled: "false",
        interfaces: "",
        arp: "enabled",
        submit: 'Add',
        error: false,
        edit: false
      },
      errors: {},
      errorMessage: '',
      interfacevlan: [],
      interfaces: []
    }
  },
  methods: {
    addOrUpdateModal(vlan) {
      if( vlan === undefined || vlan === '' )
      {
        this.forms.id = '';
        this.forms.name = '';
        this.forms.vlanid = '';
        this.forms.disabled = 'false';
        this.forms.interfaces = '';
        this.forms.arp = 'enabled';
        this.forms.edit = false;
        this.forms.submit = 'Add';
      }
      else
      {
        this.forms.id = vlan[".id"];
        this.forms.name = vlan.name;
        this.forms.vlanid = vlan["vlan-id"];
        this.forms.disabled = vlan.disabled;
        this.forms.interfaces = vlan.interface;
        this.forms.arp = vlan.arp;
        this.forms.edit = true;
        this.forms.submit = 'Save';
      }
      this.forms.error = false;
      this.errors = {};
      this.errorMessage = '';
      UIkit.modal('#modal').show();
      console.log(this.forms);
    },
    showVlan() {
      axios({
        method: 'get',
        url: this.url + '/api/mikrotik/vlan/' + this.device.device_id
      }).then(res => {
        let result = res.data;
        this.interfacevlan = result.result;
      }).catch(err => {
        console.log(err.response.status);
      });
    },
    showInterface() {
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
    addOrUpdateVlan()
    {
      this.errors = {};
      if( this.forms.name === '' )
      {
        this.forms.error = true;
        this.errors.name = 'Name must be required';
      }
      if( this.forms.vlanid === '' )
      {
        this.forms.error = true;
        this.errors.vlanid = 'Vlan ID must be required';
      }
      if( this.forms.interfaces === '' )
      {
        this.forms.error = true;
        this.errors.interfaces = 'Interface must be required';
      }
      if( this.forms.error === true )
      {
        this.forms.error = false;
        return false;
      }

      var method, url;
      if( this.forms.edit === true )
      {
        method = 'put'; url = this.url + '/api/mikrotik/update/vlan/' + this.device.device_id;
      }
      else
      {
        method = 'post'; url = this.url + '/api/mikrotik/add/vlan/' + this.device.device_id;
      }
      this.forms.submit = '<span uk-spinner></span>';
      axios({
        method: method,
        url: url,
        params: {
          id: this.forms.id,
          vlanname: this.forms.name,
          vlanid: this.forms.vlanid,
          arp: this.forms.arp,
          iface: this.forms.interfaces,
          disabled: this.forms.disabled
        }
      }).then( res => {
        let result = res.data;
        swal({
          title: 'Success',
          text: result.response,
          icon: 'success',
          timer: 5000
        });
        this.showVlan();
        this.errors = {};
        this.errorMessage = '';
        setTimeout(function(){ UIkit.modal('#modal').hide(); }, 3000);
      }).catch( err => {
        swal({
          title: 'Whoops',
          text: 'An error occured',
          icon: 'error',
          dangerMode: true
        });
        if( err.response.status === 409 )
        {
          this.errorMessage = err.response.data.response;
        }
        else
        {
          this.errorMessage = err.response.statusText;
        }
      });
      this.forms.submit = 'Add';
      if( this.forms.edit === true ) this.forms.submit = 'Save';
    },
    deleteVlan(id, name) {
      swal({
        title: 'Confirmation',
        text: 'Are you sure want to remove ' + name + '?',
        icon: 'warning',
        dangerMode: true,
        buttons: {
          cancel: true,
          confirm: { value: true, text: 'Remove' }
        }
      }).then( val => {
        if( val )
        {
          axios({
            method: 'delete',
            url: this.url + '/api/mikrotik/delete/vlan/' + this.device.device_id + '?id=' + id + '&vlanname=' + name
          }).then( res => {
            swal({
              title: 'Whoops',
              text: res.data.response,
              icon: 'success',
              timer: 3000
            });
            this.showVlan();
          }).catch( err => {
            swal({
              title: 'Whoops',
              text: 'An error occured',
              icon: 'error',
              dangerMode: true
            });
          });
        }
      })
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
    this.showVlan();
  }
}
</script>
