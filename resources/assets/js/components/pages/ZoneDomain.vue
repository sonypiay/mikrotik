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
            <label class="uk-form-label">Zone Domain ID</label>
            <div class="uk-form-controls">
              <input type="text" class="uk-input" placeholder="Example: TNB" v-model="forms.domain_id">
            </div>
          </div>
          <div class="uk-margin">
            <label class="uk-form-label">Zone Domain Name</label>
            <div class="uk-form-controls">
              <input type="text" class="uk-input" placeholder="Example: Tanah Abang" v-model="forms.domain_name">
            </div>
          </div>
          <div class="uk-margin">
            <label class="uk-form-label">Region</label>
            <div class="uk-form-controls">
              <select class="uk-select" v-model="forms.region">
                <option v-for="reg in region" v-text="reg.region_name" v-bind:value="reg.region_id"></option>
              </select>
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
        <a @keyup.enter="listzonedomain( pagination.path + '?page=' + pagination.current )" class="uk-form-icon" uk-icon="search"></a>
        <input @keyup.enter="listzonedomain( pagination.path + '?page=' + pagination.current )" type="search" class="uk-width-1-1 uk-input navbarformsearch" placeholder="Search..." v-model="keywords">
      </div>
    </div>
  </header>

  <div class="uk-card uk-card-body">
    <h3>Zone Domain</h3>
    <div class="uk-text-center">
      <a class="uk-button uk-button-default" @click="addZoneDomain()">Add Zone Domain</a>
    </div>
    <div class="uk-margin-top uk-overflow-auto">
      <table class="uk-table uk-table-middle uk-table-hover uk-table-divider uk-table-small uk-table-condensed">
        <thead>
          <tr>
            <th>#</th>
            <th>Domain ID</th>
            <th>Domain Name</th>
            <th>Region</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="domain in zonedomain.results">
            <td>
              <a  class="uk-button uk-button-small uk-button-default" @click="updateZoneDomain(domain)"><span uk-icon="pencil"></span></a>
              <a class="uk-button uk-button-small uk-button-default" @click="deleteZoneDomain(domain.id)"><span uk-icon="trash"></span></a>
            </td>
            <td>{{ domain.region_domain_id }}</td>
            <td>{{ domain.region_domain_name }}</td>
            <td>{{ domain.region_name }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
</template>

<script>
export default {
  props: ['url', 'region'],
  data() {
    return {
      forms: {
        id: 0,
        domain_id: '',
        domain_name: '',
        region: 'null',
        edit: false
      },
      keywords: '',
      pagination: {
        current: 1,
        next: '',
        prev: '',
        last: '',
        path: this.url + '/zone/domain/listzonedomain'
      },
      zonedomain: {
        total: 0,
        results: []
      }
    }
  },
  methods: {
    addZoneDomain() {
      this.forms.id = 0;
      this.forms.domain_id = '';
      this.forms.domain_name = '';
      this.forms.region = 'null';
      this.forms.edit = false;
      UIkit.modal('#addOrUpdate').show();
    },
    updateZoneDomain(zone) {
      this.forms.id = zone.id;
      this.forms.domain_id = zone.region_domain_id;
      this.forms.domain_name = zone.region_domain_name;
      this.forms.region = zone.region_id;
      this.forms.edit = true;
      console.log(this.forms);
      UIkit.modal('#addOrUpdate').show();
    },
    deleteZoneDomain(id) {
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
            url: this.url + '/zone/domain/delete/'  + id
          }).then( res => {
            swal({
              title: 'Success',
              text: res.data.statusText,
              icon: 'success',
              timer: 3000
            });
            this.listzonedomain();
          }).catch( err => {
            swal({
              title: 'Error',
              text: err.response.status + ' ' + err.response.statusText,
              icon: 'error',
              dangerMode: true
            });
          });
        }
      })
    },
    addOrUpdateZone() {
      if( this.forms.domain_id === '' )
      {
        swal({
          title: 'Warning',
          text: 'Please fill the Domain ID',
          icon: 'warning',
          dangerMode: true
        });
      }
      else if( this.forms.domain_name === '' )
      {
        swal({
          title: 'Warning',
          text: 'Please fill the Domain Name',
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
          url = this.url + '/zone/domain/add';
        }
        else
        {
          method = 'put';
          url = this.url + '/zone/domain/update/' + this.forms.id;
        }

        axios({
          method: method,
          url: url,
          headers: {
            'content-type': 'application/json'
          },
          params: {
            domain_id: this.forms.domain_id,
            domain_name: this.forms.domain_name,
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
            this.forms.id = 0;
            this.forms.domain_id = '';
            this.forms.domain_name = '';
            this.forms.edit = false;
          }
          setTimeout(function(){ UIkit.modal('#addOrUpdate').hide(); }, 2000);
          this.listzonedomain();
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
    listzonedomain(pages) {
      if( pages === undefined )
        pages = this.url + '/zone/domain/listzonedomain?page=' + this.pagination.current + '&keywords=' + this.keywords;
      else
        pages = pages + '&keywords=' + this.keywords;

      axios({
        method: 'get',
        url: pages
      }).then(res => {
        let result = res.data;
        this.zonedomain = {
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
    this.listzonedomain();
  }
}
</script>
