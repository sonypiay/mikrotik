<template lang="html">
  <div>
    <h3>Graph</h3>
    <div class="uk-grid-small" uk-grid>
      <div class="uk-width-1-3@xl uk-width-1-3@l uk-width-1-2@m uk-width-1-2@s" v-for="i in 5">
        <div class="uk-card uk-card-body uk-card-primary">
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
      interfaces: []
    }
  },
  methods: {
    getInterface() {
      axios({
        method: 'get',
        url: this.url + '/api/mikrotik/interface/' + this.device.device_id
      }).then(res => {
        let result = res.data;
        this.interfaces = result.result;
        console.log( this.interfaces );
      }).catch(err => {
        console.log(err.response.status);
      });
    }
  },
  mounted() {
    this.getInterface();
  }
}
</script>
