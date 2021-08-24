<template>
  <div class="landing-wrapper">
    <div class="back"></div>
    <div class="info-view">
      <v-row>
        <v-col cols="12">
          <img class="logo" :src="appLogoF" alt="" />
        </v-col>
        <v-col cols="12">
          <h1 style="text-align: center">
            Trade : Copy : Invest : FX
            <br />
            <br />
          </h1>
          <h3 style="text-align: center">Discover, the Undiscovered</h3>
        </v-col>
        <v-col cols="12" md="6">
          <v-btn color="error" block @click="gotoSignUp"> Sign Up </v-btn>
        </v-col>
        <v-col cols="12" md="6">
          <v-btn color="primary" block @click="gotoLogin"> Sign In </v-btn>
        </v-col>
      </v-row>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  data() {
    return {};
  },
  methods: {
    gotoLogin() {
      this.$router.push("/session/login");
    },
    gotoSignUp() {
      this.$router.push("/session/sign-up");
    },
  },
  computed: {
    ...mapGetters(["appLogo", "darkLogo"]),
    appLogoF() {
      // if (this.$vuetify.theme.dark)
      return this.appLogo;
      // return this.darkLogo;
    },
  },
};
</script>