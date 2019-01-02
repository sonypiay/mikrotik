<template lang="html">
<div>
  <!-- modal -->
  <div id="addorUpdate" class="uk-modal" uk-modal>
    <div class="uk-modal-dialog">
      <div class="uk-modal-body">
        <h3>
          <span v-if="forms.edit">Update User</span>
          <span v-else>Add User</span>
        </h3>
        <form class="uk-form-stacked uk-margin-top" @submit.prevent="addOrUpdateUser()">
          <div class="uk-margin">
            <label class="uk-form-label">Fullname</label>
            <div class="uk-form-controls">
              <input type="text" class="uk-input" v-model="forms.fullname">
            </div>
          </div>
          <div class="uk-margin">
            <label class="uk-form-label">Username</label>
            <div class="uk-form-controls">
              <input type="text" class="uk-input" v-model="forms.username">
            </div>
          </div>
          <div class="uk-margin">
            <label class="uk-form-label">Password</label>
            <div class="uk-form-controls">
              <input type="password" class="uk-input" v-model="forms.password">
            </div>
          </div>
          <div class="uk-margin">
            <label class="uk-form-label">Privilege</label>
            <div class="uk-form-controls">
              <select class="uk-select" v-model="forms.privilege">
                <option value="admin">Administrator</option>
                <option value="user">User</option>
              </select>
            </div>
          </div>
          <div class="uk-margin">
            <button id="submit" class="uk-button uk-button-primary">
              <span v-if="forms.edit">Save</span>
              <span v-else>Add</span>
            </button>
            <a class="uk-button uk-button-default uk-modal-close">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- modal -->

  <header class="uk-navbar uk-box-shadow-small navbarsearch">
    <div class="uk-width-1-1 uk-navbar-item">
      <div class="uk-width-1-1 uk-inline">
        <a @keyup.enter="listUsers( pagination.path + '?page=' + pagination.current )" class="uk-form-icon" uk-icon="search"></a>
        <input @keyup.enter="listUsers( pagination.path + '?page=' + pagination.current )" type="search" class="uk-width-1-1 uk-input navbarformsearch" placeholder="Search..." v-model="searchuser">
      </div>
    </div>
  </header>

  <div class="uk-card uk-card-body">
    <div class="uk-card-title uk-margin-bottom">Users</div>
    <div class="uk-text-center">
      <a class="uk-button uk-button-default" @click="addUser()">Add User</a>
    </div>
    <ul class="uk-margin-top uk-margin-bottom uk-pagination">
      <li>
        <span v-if="pagination.prev">
          <a @click="listUsers( pagination.prev )">
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
          <a @click="listUsers( pagination.next )">
            <span uk-pagination-next></span>
          </a>
        </span>
        <span v-else>
          <a><span uk-pagination-next></span></a>
        </span>
      </li>
    </ul>
    <div class="uk-overflow-auto">
      <table class="uk-table uk-table-middle uk-table-hover uk-table-divider uk-table-small uk-table-condensed">
        <thead>
          <tr>
            <th>#</th>
            <th>Fullname</th>
            <th>Username</th>
            <th>Privilege</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users.results">
            <td>
              <span v-if="user.username !== 'admin'">
                <a class="uk-button uk-button-small uk-button-default" @click="updateUser(user)" uk-tooltip title="Edit" uk-icon="pencil"></a>
                <a class="uk-button uk-button-small uk-button-default" @click="deleteUser(user.user_id)" uk-tooltip title="Delete" uk-icon="trash"></a>
              </span>
            </td>
            <td>{{ user.fullname }}</td>
            <td>{{ user.username }}</td>
            <td>{{ user.privilege }}</td>
          </tr>
        </tbody>
      </table>
    </div>
    <ul class="uk-margin-top uk-margin-bottom uk-pagination">
      <li>
        <span v-if="pagination.prev">
          <a @click="listUsers( pagination.prev )">
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
          <a @click="listUsers( pagination.next )">
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
  props: ['url'],
  data() {
    return {
      searchuser: '',
      users: {
        total: 0,
        results: []
      },
      pagination: {
        current: 1,
        next: '',
        prev: '',
        last: '',
        path: this.url + '/users/listuser'
      },
      forms: {
        fullname: '',
        username: '',
        password: '',
        privilege: 'admin',
        user_id: '',
        edit: false
      },
      errors: {}
    }
  },
  methods: {
    listUsers(pages) {
      if( pages !== undefined )
        pages = pages + '&searchuser=' + this.searchuser;
      else
        pages = this.url + '/users/listuser?page=' + this.pagination.current + '&searchuser=' + this.searchuser;
      axios({
        method: 'get',
        url: pages
      }).then(res => {
        let results = res.data;
        this.users = {
          total: results.total,
          results: results.data
        };
        this.pagination = {
          current: results.current_page,
          next: results.next_page_url,
          prev: results.prev_page_url,
          last: results.last_page,
          path: results.path
        };
      }).catch(err => {
        console.log(err.response);
      });
    },
    addOrUpdateUser() {
      if( this.forms.fullname === '' )
      {
        swal('Please insert fullname',{ icon: 'warning', dangerMode: true });
      }
      else if( this.forms.username === '' )
      {
        swal('Please insert username',{ icon: 'warning', dangerMode: true });
      }
      else if( this.forms.password === '' && this.forms.edit === false )
      {
        swal('Please insert password',{ icon: 'warning', dangerMode: true });
      }
      else
      {
        if( this.forms.edit === false )
        {
          if( this.countLength( this.forms.password ) < 8 )
          {
            swal('Password must be at least 8 characters',{ icon: 'warning', dangerMode: true });
            return false;
          }
          axios({
            method: 'post',
            url: this.url + '/users/add',
            headers: { 'Content-Type': 'application/json' },
            params: {
              fullname: this.forms.fullname,
              username: this.forms.username,
              password: this.forms.password,
              privilege: this.forms.privilege
            }
          }).then(res => {
            swal({
              title: 'Success',
              text: res.data.statusText,
              icon: 'success'
            });
            this.forms = {
              fullname: '',
              username: '',
              password: '',
              privilege: 'full',
              user_id: '',
              edit: false
            };
            this.listUsers();
          }).catch(err => {
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
                text: 'Error ' + status,
                icon: 'danger',
                dangerMode: true
              });
            }
          });
        }
        else
        {
          if( this.forms.password !== '' )
          {
            if( this.countLength( this.forms.password ) < 8 )
            {
              swal('Password must be at least 8 characters',{ icon: 'warning', dangerMode: true });
              return false;
            }
          }
          axios({
            method: 'put',
            url: this.url + '/users/update/' + this.forms.user_id,
            headers: {'Content-Type': 'application/json'},
            params: {
              fullname: this.forms.fullname,
              username: this.forms.username,
              password: this.forms.password,
              privilege: this.forms.privilege
            }
          }).then(res => {
            swal({
              title: 'Success',
              text: res.data.statusText,
              icon: 'success'
            });
            this.listUsers();
          }).catch(err => {
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
                text: 'Error ' + status,
                icon: 'danger',
                dangerMode: true
              });
            }
          });
        }
      }
    },
    addUser() {
      this.forms = {
        fullname: '',
        username: '',
        password: '',
        privilege: 'admin',
        user_id: '',
        edit: false
      };
      UIkit.modal('#addorUpdate').show();
    },
    updateUser(user) {
      this.forms = {
        fullname: user.fullname,
        username: user.username,
        password: '',
        privilege: user.privilege,
        user_id: user.user_id,
        edit: true
      };
      UIkit.modal('#addorUpdate').show();
    },
    deleteUser(userid) {
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
            url: this.url + '/users/delete/' + userid
          }).then(res => {
            swal({
              title: 'Success',
              text: 'User deleted',
              icon: 'success'
            });
            this.listUsers();
          }).catch(err => {
            console.log(err.response);
          });
        }
      });
    },
    countLength: function( str )
    {
      return str.length;
    }
  },
  mounted() {
    this.listUsers();
  }
}
</script>

<style lang="css">
</style>
