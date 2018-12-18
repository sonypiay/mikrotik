<template lang="html">
<div class="uk-container uk-margin-top">
  <h3>Mikrotik Global User</h3>

  <!-- modal -->
  <div id="addOrUpdate" uk-modal>
    <div class="uk-modal-dialog">
      <div class="uk-modal-body">
        <h3>
          <span v-if="forms.edit">Update User</span>
          <span v-else>Add User</span>
        </h3>
        <form class="uk-form-stacked" @submit.prevent="addOrUpdateUser">
          <div class="uk-margin">
            <label class="uk-form-label">Username</label>
            <div class="uk-form-controls">
              <input type="text" class="uk-input" placeholder="Username" v-model="forms.username">
            </div>
          </div>
          <div class="uk-margin">
            <label class="uk-form-label">Password</label>
            <div class="uk-form-controls">
              <input type="text" class="uk-input" placeholder="Password" v-model="forms.password">
            </div>
          </div>
          <div class="uk-margin">
            <label class="uk-form-label">Port API</label>
            <div class="uk-form-controls">
              <input type="text" class="uk-input" placeholder="Port" v-model="forms.port">
            </div>
          </div>
          <div class="uk-margin">
            <button class="uk-button uk-button-primary">
              <span v-if="forms.edit">Save</span>
              <span v-else>Add</span>
            </button>
            <a class="uk-button uk-button-default uk-modal-close" >Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- modal -->

  <div class="uk-card uk-card-body uk-card-default">
    <div class="uk-grid-small" uk-grid>
      <div class="uk-width-1-4@xl uk-width-1-4@l uk-width-1-2@m uk-width-1-2@s">
        <div class="uk-width-1-1 uk-inline">
          <a class="uk-form-icon" uk-icon="search"></a>
          <input type="search" class="uk-width-1-1 uk-input" placeholder="Search..." v-model="searchuser">
        </div>
      </div>
      <div class="uk-width-1-4@xl uk-width-1-2@m uk-width-1-2@s">
        <a class="uk-button uk-button-default" @click="addUser()">Add User</a>
      </div>
    </div>

    <ul class="uk-margin-top uk-pagination">
      <li>
        <a href="#">
          <span v-if="pagination.prev">
            <span @click="listUsers( pagination.prev )" uk-pagination-prev></span>
          </span>
          <span v-else>
            <span uk-pagination-prev></span>
          </span>
        </a>
      </li>
      <li> <span>Page 1 of 1</span> </li>
      <li>
        <a href="#">
          <span v-if="pagination.next">
            <span @click="listUsers( pagination.next )" uk-pagination-next></span>
          </span>
          <span v-else>
            <span uk-pagination-next></span>
          </span>
        </a>
      </li>
    </ul>

    <div class="uk-margin-top uk-overflow-auto">
      <table class="uk-table uk-table-middle uk-table-hover uk-table-divider uk-table-small uk-table-condensed">
        <thead>
          <tr>
            <th>#</th>
            <th>Username</th>
            <th>Password</th>
            <th>Port</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users.results">
            <td>
              <a @click="updateUser(user)" class="uk-button uk-button-default uk-button-small">
                <span uk-icon="pencil"></span>
              </a>
              <a @click="deleteUser(user.id)" class="uk-button uk-button-default uk-button-small">
                <span uk-icon="trash"></span>
              </a>
            </td>
            <td>{{ user.username_mikrotik }}</td>
            <td>{{ user.password_mikrotik }}</td>
            <td>{{ user.port }}</td>
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
        username: '',
        password: '',
        port: 8728,
        id: '',
        edit: false
      },
      users: {
        total: 0,
        results: []
      },
      searchuser: '',
      pagination: {
        current: 1,
        next: '',
        prev: '',
        last: 1,
        path: this.url + '/usermikrotik/listuser'
      }
    }
  },
  methods: {
    listUsers(pages) {
      if( pages !== undefined )
        pages = pages + '&searchuser=' + this.searchuser;

      pages = this.url + '/usermikrotik/listuser?page=' + this.pagination.current + '&searchuser=' + this.searchuser;
      axios({
        method: 'get',
        url: pages
      }).then( res => {
        let result = res.data;
        this.users = {
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
      }).catch( err => {
        console.log(err.response.status);
      });
    },
    addUser() {
      this.forms.username = '';
      this.forms.password = '';
      this.forms.port = 8728;
      this.forms.id = '';
      this.forms.edit = false;
      UIkit.modal('#addOrUpdate').show();
    },
    updateUser(user) {
      this.forms.username = user.username_mikrotik;
      this.forms.password = user.password_mikrotik;
      this.forms.port = user.port;
      this.forms.id = user.id;
      this.forms.edit = true;
      UIkit.modal('#addOrUpdate').show();
    },
    deleteUser(id) {
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
      }).then( value => {
        if( value )
        {
          axios({
            method: 'delete',
            url: this.url + '/usermikrotik/delete/' + id
          }).then(res => {
            swal({
              title: 'Success',
              text: 'User mikrotik deleted',
              icon: 'success'
            });
            this.listUsers();
          }).catch(err => {
            console.log(err.response);
          });
        }
      });
    },
    addOrUpdateUser() {
      if( this.forms.username === '' )
      {
        swal({
          title: 'Warning',
          text: 'Please fill the username',
          icon: 'warning',
          dangerMode: true
        });
      }
      else if( isNaN( this.forms.port ) )
      {
        swal({
          title: 'Warning',
          text: 'Port mikrotik should be a number',
          icon: 'warning',
          dangerMode: true
        });
      }
      else
      {
        var url = '';
        var method = '';
        if( this.forms.edit === false )
        {
          url = this.url + '/usermikrotik/add';
          method = 'post';
        }
        else
        {
          url = this.url + '/usermikrotik/update/' + this.forms.id;
          method = 'put';
        }

        axios({
          method: method,
          url: url,
          headers: { 'Content-Type': 'application/json' },
          params: {
            username: this.forms.username,
            password: this.forms.password,
            port: this.forms.port
          }
        }).then(res => {
          swal({
            title: 'Success',
            text: res.data.statusText,
            icon: 'success'
          });
          this.listUsers();
          setTimeout(function(){ UIkit.modal('#addOrUpdate').hide(); }, 2000);
          if( this.forms.edit === false )
          {
            this.forms.username = '';
            this.forms.password = '';
            this.forms.port = 8728;
          }
        }).catch(err => {
          console.log(err.response.status);
        });
      }
    }
  },
  mounted() {
    this.listUsers();
  }
}
</script>
