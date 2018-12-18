<template lang="html">
<div>
  <div id="addOrUpdate" uk-modal>
    <div class="uk-modal-dialog">
      <div class="uk-modal-body">
        <h3>
          <span v-if="forms.edit">Update Zone</span>
          <span v-else>Add New Zone</span>
        </h3>
        <form class="uk-form-stacked" @submit.prevent="addOrUpdateZone">
          <div class="uk-margin">
            <label class="uk-form-label">Region ID</label>
            <div class="uk-form-controls">
              <input type="text" class="uk-input" placeholder="Example: JKP" v-model="forms.region_id">
            </div>
          </div>
          <div class="uk-margin">
            <label class="uk-form-label">Region Name</label>
            <div class="uk-form-controls">
              <input type="text" class="uk-input" placeholder="Example: Jakarta Pusat" v-model="forms.region_name">
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
        <a @click="listzoneregion( pagination.path + '?page=' + pagination.current )" class="uk-form-icon" uk-icon="search"></a>
        <input @keyup.enter="listzoneregion( pagination.path + '?page=' + pagination.current )" type="search" class="uk-input navbarformsearch" v-model="keywords" placeholder="Search...">
      </div>
    </div>
  </header>

  <div class="uk-card uk-card-body">
    <h3>Zone Region</h3>
    <div class="uk-text-center">
      <a class="uk-button uk-button-default" @click="addZoneRegion()">Add Zone Region</a>
    </div>

    <div class="uk-margin-top uk-overflow-auto">
      <table class="uk-table uk-table-middle uk-table-hover uk-table-divider uk-table-small uk-table-condensed">
        <thead>
          <tr>
            <th>#</th>
            <th>Region ID</th>
            <th>Region Name</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="zone in zoneregion.results">
            <td>
              <a  class="uk-button uk-button-small uk-button-default" @click="updateZoneRegion(zone)"><span uk-icon="pencil"></span></a>
              <a class="uk-button uk-button-small uk-button-default" @click="deleteZoneRegion(zone.id)"><span uk-icon="trash"></span></a>
            </td>
            <td>{{ zone.region_id }}</td>
            <td>{{ zone.region_name }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
</template>

<script>
export default {
  props: ['url'],
  data() {
    return {
      forms: {
        id: 0,
        region_id: '',
        region_name: '',
        edit: false
      },
      keywords: '',
      pagination: {
        current: 1,
        next: '',
        prev: '',
        last: '',
        path: this.url + '/zone/region/listzoneregion'
      },
      zoneregion: {
        total: 0,
        results: []
      }
    }
  },
  methods: {
    addZoneRegion() {
      this.forms.id = 0;
      this.forms.region_id = '';
      this.forms.region_name = '';
      this.forms.edit = false;
      UIkit.modal('#addOrUpdate').show();
    },
    updateZoneRegion(zone) {
      this.forms.id = zone.id;
      this.forms.region_id = zone.region_id;
      this.forms.region_name = zone.region_name;
      this.forms.edit = true;
      UIkit.modal('#addOrUpdate').show();
    },
    deleteZoneRegion(id) {
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
            url: this.url + '/zone/region/delete/'  + id
          }).then( res => {
            swal({
              title: 'Success',
              text: res.data.statusText,
              icon: 'success',
              timer: 3000
            });
            this.listzoneregion();
          }).catch( err => {
            swal({
              title: 'Error',
              text: err.respone.status + ' ' + err.response.statusText,
              icon: 'error',
              dangerMode: true
            });
          });
        }
      })
    },
    addOrUpdateZone() {
      if( this.forms.region_id === '' )
      {
        swal({
          title: 'Warning',
          text: 'Please fill the Region ID',
          icon: 'warning',
          dangerMode: true
        });
      }
      else if( this.forms.region_name === '' )
      {
        swal({
          title: 'Warning',
          text: 'Please fill the Region Name',
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
          url = this.url + '/zone/region/add';
        }
        else
        {
          method = 'put';
          url = this.url + '/zone/region/update/' + this.forms.id;
        }

        axios({
          method: method,
          url: url,
          headers: {
            'content-type': 'application/json'
          },
          params: {
            region_id: this.forms.region_id,
            region_name: this.forms.region_name
          }
        }).then( res => {
          swal({
            title: 'Success',
            text: res.data.statusText,
            icon: 'success'
          });
          if( this.forms.edit === false )
          {
            this.forms.id = 0;
            this.forms.region_id = '';
            this.forms.region_name = '';
            this.forms.edit = false;
          }
          setTimeout(function(){ UIkit.modal('#addOrUpdate').hide(); }, 2000);
          this.listzoneregion();
        }).catch( err => {
          let status = err.response.status;
          if( status === 409 )
          {
            let message = err.response.data;
            swal({
              title: 'Warning',
              text: message.statusText,
              icon: 'warning',
              dangerMode: true
            });
          }
          else
          {
            swal({
              title: 'Error',
              text: 'Whoops, something when wrong',
              icon: 'error',
              dangerMode: true
            });
          }
        });
      }
    },
    listzoneregion(pages) {
      if( pages === undefined )
        pages = this.url + '/zone/region/listzoneregion?page=' + this.pagination.current + '&keywords=' + this.keywords;
      else
        pages = pages + '&keywords=' + this.keywords;

      axios({
        method: 'get',
        url: pages
      }).then(res => {
        let result = res.data;
        this.zoneregion = {
          total: result.total,
          results: result.data
        };
        this.pagination = {
          current: result.current_page,
          next: result.next_page_url,
          prev: result.prev_page_url,
          last: result.last_page,
          path: result.path
        };
      }).catch(err => {
        console.log(err.response.status);
      });
    }
  },
  mounted() {
    this.listzoneregion();
  }
}
</script>
