<template>
  <div class="session-wrapper">
    <div class="session-left">
      <session-slider-widget></session-slider-widget>
    </div>
    <div class="session-right text-center">
      <div class="session-table-cell-signup">
        <div class="session-content">
          <router-link to="/">
            <img
              :src="appLogoF"
              class="img-responsive mb-4"
              style="width: 70%; height: auto"
            />
          </router-link>
          <h2 class="mb-4">{{ $t("message.signUp") }}</h2>
          <p class="fs-14">
            {{ $t("message.havingAnAccount") }}
            <router-link to="/session/login">{{
              $t("message.login")
            }}</router-link>
          </p>
          <v-form v-model="valid" class="mb-5">
            <v-row>
              <v-col cols="12" class="py-0">
                <v-text-field
                  label="Username"
                  v-model="name"
                  :rules="nameRules"
                  :counter="30"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="12" class="py-0">
                <v-text-field
                  label="E-mail"
                  v-model="email"
                  :rules="emailRules"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="12" class="py-0">
                <v-menu
                  ref="dateref"
                  :close-on-content-click="false"
                  v-model="dateref"
                  transition="scale-transition"
                  offset-y
                  :nudge-right="40"
                  min-width="290px"
                  :return-value.sync="date"
                >
                  <template v-slot:activator="{ on }">
                    <v-text-field
                      v-on="on"
                      label="Date Of Birth"
                      v-model="date"
                      prepend-icon="event"
                      readonly
                    ></v-text-field>
                  </template>
                  <v-date-picker v-model="date" no-title scrollable>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" @click="dateref = false"
                      >Cancel</v-btn
                    >
                    <v-btn color="warning" @click="$refs.dateref.save(date)"
                      >OK</v-btn
                    >
                  </v-date-picker>
                </v-menu>
              </v-col>
              <v-col cols="12" md="8" class="py-0">
                <v-text-field label="Address" v-model="address"> </v-text-field>
              </v-col>
              <v-col cols="12" md="4" class="py-0">
                <v-text-field label="ZipCode" v-model="zipcode"> </v-text-field>
              </v-col>
              <v-col cols="12" class="py-0">
                <v-select
                  :items="countryList"
                  v-model="country"
                  label="Country"
                  item-text="name"
                  item-value="index"
                  menu-props="auto"
                >
                  <template v-slot:selection="{ item }">
                    <v-img
                      max-width="30"
                      :src="item.flag"
                      class="flag-view"
                    ></v-img>
                    &nbsp;
                    {{ item.name }}
                  </template>
                  <template v-slot:item="{ item }">
                    <v-img
                      max-width="35"
                      :src="item.flag"
                      class="flag-view"
                    ></v-img>
                    &nbsp;
                    {{ item.name }}
                  </template>
                </v-select>
              </v-col>
              <v-col cols="12" class="py-0">
                <v-text-field
                  label="Phone Number"
                  v-model="phone"
                  type="text"
                ></v-text-field>
              </v-col>
              <v-col cols="12" class="py-0">
                <v-text-field
                  label="Password"
                  v-model="password"
                  :rules="passwordRules"
                  type="password"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="12" class="py-0">
                <v-checkbox
                  color="primary"
                  label="I read and agree to the Terms of Service."
                  v-model="agree"
                ></v-checkbox>
              </v-col>
              <v-col cols="12" class="py-0">
                <v-btn
                  large
                  @click="signupWithLaravel"
                  block
                  color="primary"
                  class="mb-3"
                  :disabled="!agree"
                >
                  {{ $t("message.signUp") }}
                </v-btn>
              </v-col>
              <v-col cols="12">
                <v-btn plain @click="isOpen = true">{{
                  $t("message.termsOfService")
                }}</v-btn>
              </v-col>
            </v-row>
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
import axios from "axios";
import webServices from "WebServices";
import { mapGetters } from "vuex";
import TermsOfService from "./TermsOfService";

export default {
  components: {
    SessionSliderWidget,
    TermsOfService,
  },
  data() {
    return {
      valid: false,
      isOpen: false,
      agree: false,
      name: "",
      nameRules: [
        (v) => !!v || "Name is required",
        (v) => v.length <= 30 || "Name must be less than 30 characters",
      ],
      email: "",
      emailRules: [
        (v) => !!v || "E-mail is required",
        (v) =>
          /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) ||
          "E-mail must be valid",
      ],
      country: "",
      password: "",
      passwordRules: [(v) => !!v || "Password is required"],
      brand: AppConfig.brand,
      address: "",
      zipcode: "",
      phone: "",
      dateref: false,
      date: null,
      dateRules: [(v) => !!v || "Date of Birth is required"],
      countryList: [],
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
    signupWithLaravel() {
      let userDetail = {
        name: this.name,
        email: this.email,
        password: this.password,
        date_of_birth: this.date,
        phone: this.phone,
        country: this.countryList[this.country].name,
        flag: this.countryList[this.country].flag,
        address: this.address,
        zipcode: this.zipcode,
      };
      this.$store.dispatch("signupUserWithLaravelPassport", {
        userDetail,
      });
    },
  },
  mounted() {
    axios
      .get(`${webServices.countryListURL}`, {
        headers: { "Content-Type": "application/json" },
      })
      .then(({ data }) => {
        this.countryList = data.map((list, index) => ({
          index,
          name: list.name,
          flag: list.flag,
        }));
      });
  },
};
</script>