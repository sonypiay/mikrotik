<template lang="html">
  <div class="uk-container uk-margin-top">
    <div class="uk-margin-top">
      <ul class="uk-flex-center" uk-tab>
        <li><a @click="viewPage('locationid')">Location ID</a></li>
        <li><a @click="viewPage('radiusip')">Hotspot Address</a></li>
        <li><a @click="viewPage('walledgarden')">Walled Garden</a></li>
        <li><a @click="viewPage('bandwidth')">Bandwidth Limit</a></li>
        <li><a @click="viewPage('sessiontimeout')">Session Timeout</a></li>
      </ul>
    </div>

    <div v-if="component === 'locationid'">
      <locationid :url="url" :device="device" />
    </div>
    <div v-else-if="component === 'radiusip'">
      <radiusip :url="url" :device="device" />
    </div>
    <div v-else-if="component === 'walledgarden'">
      <walledgarden :url="url" :device="device" />
    </div>
    <div v-else-if="component === 'bandwidth'">
      <bandwidth :url="url" :device="device" />
    </div>
    <div v-else>
      <sessiontimeout :url="url" :device="device" />
    </div>
  </div>
</template>

<script>
import locationid from '../api/LocationID.vue';
import radiusip from '../api/RadiusIP.vue';
import walledgarden from '../api/WalledGarden.vue';
import sessiontimeout from '../api/SessionTimeout.vue';
import bandwidth from '../api/Bandwidth.vue';

export default {
  props: ['url','device'],
  data() {
    return { component: 'locationid' }
  },
  components: {
    locationid, radiusip, walledgarden, sessiontimeout, bandwidth
  },
  methods: { viewPage(menu) { this.component = menu; } }
}
</script>
