<template lang="html">
<div class="uk-container uk-margin-top">
  <div class="uk-margin-top">
    <ul class="uk-flex-center" uk-tab>
      <li><a @click="viewPage('deviceinfo')">Device Info</a></li>
      <li><a @click="viewPage('log')">Log</a></li>
      <li><a @click="viewPage('graph')">Graph</a></li>
    </ul>
  </div>

  <div v-if="component === 'deviceinfo'">
    <deviceinfo :url="url" :device="device" :session="session" />
  </div>
  <div v-else-if="component === 'log'">
    <logview :url="url" :device="device" :session="session" />
  </div>
  <div v-else>
    <graphtool :url="url" :device="device" :session="session" />
  </div>
</div>
</template>

<script>
import logview from '../api/Log.vue';
import deviceinfo from '../api/DeviceInfo.vue';
import graphtool from '../api/Graph.vue';

export default {
  props: ['url', 'device', 'session'],
  components: {
    logview,
    deviceinfo,
    graphtool
  },
  data() {
    return { component: 'deviceinfo' }
  },
  methods: { viewPage(menu) { this.component = menu; } }
}
</script>
