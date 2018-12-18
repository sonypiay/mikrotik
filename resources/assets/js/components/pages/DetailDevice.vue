<template lang="html">
<div class="uk-container uk-margin-top">
  <h3>{{ device.device_name }}</h3>
  <div class="uk-margin-top">
    <ul class="uk-width-expand" uk-tab>
      <li><a @click="viewPage('deviceinfo')">Device Info</a></li>
      <li><a>Interface <span uk-icon="triangle-down"></span></a>
        <div uk-dropdown>
            <ul class="uk-nav uk-dropdown-nav">
                <li><a @click="viewPage('interface')">All Interface</a></li>
                <li><a @click="viewPage('wireless')">Wireless</a></li>
                <li><a @click="viewPage('vlan')">Vlan</a></li>
            </ul>
        </div>
      </li>
      <li><a @click="viewPage('log')">Log</a></li>
      <li><a @click="viewPage('ip')">IP Address</a></li>
    </ul>
  </div>
  <div v-if="component === 'deviceinfo'">
    <deviceinfo :url="url" :device="device" />
  </div>
  <div v-else-if="component === 'log'">
    <logview :url="url" :device="device" />
  </div>
  <div v-else-if="component === 'interface'">
    <interfaces :url="url" :device="device" />
  </div>
  <div v-else-if="component === 'vlan'">
    <vlaninterfaces :url="url" :device="device" />
  </div>
  <div v-else-if="component === 'ip'">
    <ipaddress :url="url" :device="device" />
  </div>
</div>
</template>

<script>
import interfaces from '../api/Interface.vue';
import logview from '../api/Log.vue';
import ipaddress from '../api/IPAddress.vue';
import deviceinfo from '../api/DeviceInfo.vue';
import vlaninterfaces from '../api/VlanInterface.vue';

export default {
  props: ['url', 'device'],
  components: {
    interfaces,
    logview,
    ipaddress,
    deviceinfo,
    vlaninterfaces
  },
  data() {
    return { component: 'deviceinfo' }
  },
  methods: { viewPage(menu) { this.component = menu; } }
}
</script>
