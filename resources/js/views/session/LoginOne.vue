<template>
  <div class="session-wrapper">
    <div class="session-left">
      <session-slider-widget></session-slider-widget>
    </div>
    <div class="session-right text-center">
      <div class="session-table-cell">
        <div class="session-content">
          <router-link to="/">
            <img
              :src="appLogoF"
              class="img-responsive mb-4"
              style="width: 70%; height: auto"
            />
          </router-link>
          <h2 class="mb-4">{{ $t("message.loginWithOrigin") }}</h2>
          <!-- <p class="fs-14">{{$t('message.enterUsernameAndPasswordToAccessControlPanelOf')}} {{brand}}.</p> -->
          <v-form v-model="valid" class="mb-5">
            <v-text-field
              label="E-mail ID"
              v-model="email"
              :rules="emailRules"
              required
            ></v-text-field>
            <v-text-field
              label="Password"
              v-model="password"
              type="password"
              :rules="passwordRules"
              v-on:keyup="validateEmailAddress"
              required
            ></v-text-field>
            <v-checkbox
              color="primary"
              label="Remember me"
              v-model="checkbox"
            ></v-checkbox>
            <router-link class="mb-2" to="/session/forgot-password"
              >{{ $t("message.forgotPassword") }}?
            </router-link>
            <div>
              <v-btn
                large
                class="mb-2"
                @click="signInWithLaravelPassport"
                block
                color="primary"
              >
                {{ $t("message.loginWithOrigin") }}
              </v-btn>
              <v-btn
                large
                @click="onCreateAccount"
                block
                color="warning"
                class="mb-2"
              >
                {{ $t("message.createAccount") }}
              </v-btn>
            </div>
            <v-btn plain @click="isOpen = true">{{
              $t("message.termsOfService")
            }}</v-btn>
          </v-form>
        </div>
      </div>
    </div>
    <terms-of-service
      :isOpen="isOpen"
      @close="isOpen = false"
    ></terms-of-service>
  </div>
</template>

<script>
import SessionSliderWidget from "Components/Widgets/SessionSlider";
import AppConfig from "Constants/AppConfig";
import { mapGetters } from "vuex";
import TermsOfService from "./TermsOfService";

export default {
  components: {
    SessionSliderWidget,
    TermsOfService,
  },
  data() {
    return {
      checkbox: false,
      isOpen: false,
      valid: false,
      email: "",
      emailRules: [
        (v) => !!v || "E-mail is required",
        (v) =>
          /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) ||
          "E-mail must be valid",
      ],
      password: "",
      passwordRules: [(v) => !!v || "Password is required"],
      brand: AppConfig.brand,
    };
  },
  computed: {
    ...mapGetters(["appLogo", "darkLogo"]),
    ...{
      appLogoF() {
        // if (this.$vuetify.theme.dark)
        return this.appLogo;
        // return this.darkLogo;
      },
    },
  },
  methods: {
    signInWithLaravelPassport() {
      const user = {
        email: this.email,
        password: this.password,
      };
      this.$store.dispatch("signInWithLaravelPassport", {
        user,
      });
    },
    onCreateAccount() {
      this.$router.push("/session/sign-up");
    },
    validateEmailAddress: function (e) {
      if (e.keyCode === 13) {
        this.signInWithLaravelPassport();
      }
    },
  },
};
</script>