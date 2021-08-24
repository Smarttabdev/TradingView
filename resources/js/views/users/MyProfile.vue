<template>
  <div>
    <page-title-bar></page-title-bar>
    <v-container fluid class="grid-list-xl pt-0 mt-n3">
      <v-row>
        <v-col cols="12" md="12" lg="12" sm="12" class="col-height-auto">
          <div v-if="loading">
            <div class="profile-head app-card mb-30 mt-30">
              <div class="user-list-content">
                <div class="text-center">
                  <h3 class="fw-bold">Loading ...</h3>
                </div>
              </div>
            </div>
          </div>
          <div v-else>
            <div v-if="user" class="profile-head app-card mb-30">
              <div class="user-profile-widget top-author-wrap">
                <div class="avatar-wrap mb-50 pos-relative">
                  <span class="overlay-content"></span>
                  <div class="user-info">
                    <input
                      type="file"
                      accept="image/*"
                      ref="profileimage"
                      style="display: none"
                      @change="changeAvatar"
                    />
                    <v-tooltip right>
                      <template v-slot:activator="{ on }">
                        <img
                          v-on="on"
                          @click="$refs.profileimage.click()"
                          :src="
                            user.avatar
                              ? user.avatar
                              : '/static/avatars/default.png'
                          "
                          alt="reviwers"
                          width="100"
                          height="100"
                          class="img-responsive rounded-circle mr-3"
                          style="cursor: pointer"
                        />
                      </template>
                      <span>Click Here to change avatar</span>
                    </v-tooltip>
                    <div class="white--text pt-7">
                      <h1 class="mb-0" style="text-transform: uppercase">
                        {{ user.name }}
                      </h1>
                    </div>
                  </div>
                </div>
                <div class="author-detail-wrap">
                  <div class="pa-3 authors-info">
                    <ul class="list-unstyled author-contact-info mb-2">
                      <li class="d-flex px-4 align-center">
                        <span class="mr-3 d-custom-flex align-items-left w-30">
                          <span><i class="zmdi zmdi-email"></i> Email</span>
                        </span>
                        <span
                          class="
                            fs-20
                            grey--text
                            fw-normal
                            d-custom-flex
                            align-items-left
                            w-70
                          "
                          >{{ user.email }}</span
                        >
                      </li>
                      <li class="d-flex px-4 align-center">
                        <span class="mr-3 d-custom-flex align-items-left w-30">
                          <span
                            ><i class="zmdi zmdi-email-open"></i> Contact
                            Me</span
                          >
                        </span>
                        <span
                          class="
                            fs-20
                            grey--text
                            fw-normal
                            d-custom-flex
                            align-items-left
                            w-70
                          "
                          >{{
                            user.isCanContact && user.isCanContact == 1
                              ? "ON"
                              : "OFF"
                          }}</span
                        >
                      </li>
                      <li class="d-flex px-4 align-center">
                        <span class="mr-3 d-custom-flex align-items-left w-30">
                          <span
                            ><i class="zmdi zmdi-calendar-check"></i> Experience
                            Year</span
                          >
                        </span>
                        <span
                          class="
                            fs-20
                            grey--text
                            fw-normal
                            d-custom-flex
                            align-items-left
                            w-70
                          "
                          >{{ user.experience_year }}</span
                        >
                      </li>
                      <li class="d-flex px-4 align-center">
                        <span class="mr-3 d-custom-flex align-items-left w-30">
                          <span
                            ><i class="zmdi zmdi-format-list-bulleted"></i>
                            Experience History</span
                          >
                        </span>
                        <span
                          class="
                            fs-20
                            grey--text
                            fw-normal
                            d-custom-flex
                            align-items-left
                            w-70
                          "
                          >{{ user.experience_history }}</span
                        >
                      </li>
                      <li class="d-flex px-4 align-center">
                        <span class="mr-3 d-custom-flex align-items-left w-30">
                          <span
                            ><i class="zmdi zmdi-accounts-alt"></i> Group</span
                          >
                        </span>
                        <span
                          class="
                            fs-20
                            grey--text
                            fw-normal
                            d-custom-flex
                            align-items-left
                            w-70
                          "
                          >{{ groupLabel }}</span
                        >
                      </li>
                      <li class="d-flex px-4 align-center">
                        <span class="mr-3 d-custom-flex align-items-left w-30">
                          <span><i class="zmdi zmdi-phone-msg"></i> Phone</span>
                        </span>
                        <span
                          class="
                            fs-20
                            grey--text
                            fw-normal
                            d-custom-flex
                            align-items-left
                            w-70
                          "
                          >{{ user.phone }}</span
                        >
                      </li>
                      <li class="d-flex px-4 align-center">
                        <span class="mr-3 d-custom-flex align-items-left w-30">
                          <span
                            ><i class="zmdi zmdi-calendar-alt"></i>
                            Birthday</span
                          >
                        </span>
                        <span
                          class="
                            fs-20
                            grey--text
                            fw-normal
                            d-custom-flex
                            align-items-left
                            w-70
                          "
                          >{{ getDateFormat(user.date_of_birth) }}</span
                        >
                      </li>
                      <li class="d-flex px-4 align-center">
                        <span class="mr-3 d-custom-flex align-items-left w-30">
                          <span><i class="zmdi zmdi-map"></i> Country</span>
                        </span>
                        <span
                          class="
                            fs-20
                            grey--text
                            fw-normal
                            d-custom-flex
                            align-items-center
                            w-70
                          "
                          ><v-img :src="user.flag" max-width="30"></v-img>
                          &nbsp; {{ user.country }}
                        </span>
                      </li>
                      <li class="d-flex px-4 align-center">
                        <span class="mr-3 d-custom-flex align-items-left w-30">
                          <span><i class="zmdi zmdi-pin"></i> Address</span>
                        </span>
                        <span
                          class="
                            fs-20
                            grey--text
                            fw-normal
                            d-custom-flex
                            align-items-left
                            w-70
                          "
                          >{{ user.address }}</span
                        >
                      </li>
                      <li class="d-flex px-4 align-center">
                        <span class="mr-3 d-custom-flex align-items-left w-30">
                          <span><i class="zmdi zmdi-code"></i> Zip Code</span>
                        </span>
                        <span
                          class="
                            fs-20
                            grey--text
                            fw-normal
                            d-custom-flex
                            align-items-left
                            w-70
                          "
                          >{{ user.zipcode }}</span
                        >
                      </li>
                      <li class="d-flex px-4 align-center">
                        <span class="mr-3 d-custom-flex align-items-left w-30">
                          <span
                            ><i class="zmdi zmdi-calendar-check"></i> Member
                            Since</span
                          >
                        </span>
                        <span
                          class="
                            fs-20
                            grey--text
                            fw-normal
                            d-custom-flex
                            align-items-left
                            w-70
                          "
                          >{{ getDateFormat(user.created_at) }}</span
                        >
                      </li>
                      <li class="d-flex px-4 align-center">
                        <span class="mr-3 d-custom-flex align-items-left w-30">
                          <span><i class="zmdi zmdi-key"></i> Client ID</span>
                        </span>
                        <span
                          class="
                            fs-20
                            grey--text
                            fw-normal
                            d-custom-flex
                            align-items-left
                            w-70
                          "
                          >{{ user.client_id }}</span
                        >
                      </li>
                      <li class="d-flex px-4 align-center">
                        <span class="mr-3 d-custom-flex align-items-left w-30">
                          <span
                            ><i class="zmdi zmdi-shield-security"></i> Client
                            Secret</span
                          >
                        </span>
                        <span
                          class="
                            fs-20
                            grey--text
                            fw-normal
                            d-custom-flex
                            align-items-left
                            w-50
                          "
                        >
                          {{
                            showsecret
                              ? user.client_secret
                              : "....................."
                          }}
                        </span>
                        <span class="mr-3 d-custom-flex align-items-right w-20">
                          <v-btn
                            class="mr-3"
                            text
                            icon
                            :color="!showsecret ? 'success' : 'error'"
                            @click="showsecret = !showsecret"
                          >
                            <v-icon
                              class="zmdi zmdi-eye"
                              v-if="!showsecret"
                            ></v-icon>
                            <v-icon class="zmdi zmdi-eye-off" v-else></v-icon>
                          </v-btn>
                        </span>
                      </li>
                    </ul>
                    <ul class="d-custom-flex social-info list-unstyled">
                      <li>
                        <a class="facebook" href="https://www.facebook.com"
                          ><i class="zmdi zmdi-facebook-box"></i
                        ></a>
                      </li>
                      <li>
                        <a class="twitter" href="https://www.twitter.com"
                          ><i class="zmdi zmdi-twitter-box"></i
                        ></a>
                      </li>
                      <li>
                        <a class="linkedin" href="https://www.linkedin.com"
                          ><i class="zmdi zmdi-linkedin-box"></i
                        ></a>
                      </li>
                      <li>
                        <a class="instagram" href="https://www.instagram.com"
                          ><i class="zmdi zmdi-instagram"></i
                        ></a>
                      </li>
                    </ul>
                  </div>
                  <div class="d-custom-flex align-center px-3 pb-3">
                    <v-btn
                      fab
                      class="mr-3"
                      dark
                      color="cyan"
                      @click="onEditUser()"
                    >
                      <v-icon dark>edit</v-icon>
                    </v-btn>
                  </div>
                  <ul
                    class="
                      d-custom-flex
                      list-unstyled
                      footer-content
                      text-center
                      w-100
                      border-top-1
                      align-end
                    "
                  >
                    <li>
                      <h5 class="mb-0">{{ trading_info.providers }}</h5>
                      <span class="fs-12 grey--text fw-normal">Providers</span>
                    </li>
                    <li>
                      <h5 class="mb-0">{{ trading_info.followers }}</h5>
                      <span class="fs-12 grey--text fw-normal">Followers</span>
                    </li>
                    <li>
                      <h5 class="mb-0">{{ trading_info.copiers }}</h5>
                      <span class="fs-12 grey--text fw-normal">Copiers</span>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <div v-else class="profile-head app-card mb-30 mt-30">
              <div class="user-list-content">
                <div class="text-center">
                  <h3 class="fw-bold">User data is not available</h3>
                </div>
              </div>
            </div>
          </div>
        </v-col>
      </v-row>
      <template>
        <v-dialog v-model="open" max-width="600">
          <v-card class="pa-6">
            <v-form v-model="form.valid" ref="form" lazy-validation>
              <v-text-field
                label="Name"
                v-model="form.name"
                :rules="form.nameRules"
                :counter="30"
                required
              ></v-text-field>
              <v-text-field
                label="E-mail"
                v-model="form.email"
                :rules="form.emailRules"
                required
                readonly
              >
              </v-text-field>
              <v-checkbox
                label="Allow Contact Me"
                color="primary"
                v-model="form.isCanContact"
              />
              <v-text-field
                label="Experience Year"
                type="number"
                min="0"
                v-model="form.experienceYear"
                :rules="form.experienceYearRules"
              >
              </v-text-field>
              <v-textarea
                label="Experience History"
                v-model="form.experienceHistory"
              ></v-textarea>
              <v-text-field label="Address" v-model="form.address">
              </v-text-field>
              <v-text-field label="ZipCode" v-model="form.zipcode">
              </v-text-field>
              <v-select
                :items="countryList"
                v-model="form.country"
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
              <v-text-field label="Phone" v-model="form.phone"> </v-text-field>
              <v-text-field
                label="Password"
                v-model="form.password"
                type="password"
                required
              >
              </v-text-field>
              <v-btn
                @click="editUser"
                :disabled="!form.valid"
                color="success"
                class="mr-3"
              >
                Update
              </v-btn>
              <v-btn color="primary" @click="open = false" class="px-4"
                >Cancel</v-btn
              >
            </v-form>
          </v-card>
        </v-dialog>
      </template>
    </v-container>
  </div>
</template>

<script>
import webServices from "WebServices";
import dateformat from "dateformat";
import axios from "axios";
import Vue from "vue";
import Nprogress from "nprogress";
import { mapActions, mapGetters } from "vuex";

export default {
  data() {
    return {
      user: null,
      trading_info: null,
      loading: false,
      open: false,
      countryList: [],
      form: {
        valid: true,
        name: "",
        nameRules: [
          (v) => !!v || "Name is required",
          (v) =>
            (v && v.length <= 30) || "Name must be less than 30 characters",
        ],
        email: "",
        emailRules: [
          (v) => !!v || "E-mail is required",
          (v) =>
            /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) ||
            "E-mail must be valid",
        ],
        isCanContact: 0,
        experienceYear: 0,
        experienceYearRules: [(v) => v >= 0 || "Minimum Experience Year is 0"],
        experienceHistory: "",
        country: "",
        address: "",
        zipcode: "",
        phone: null,
        password: false,
      },
      showsecret: false,
    };
  },
  mounted() {
    this.loading = true;
    Nprogress.start();
    this.getGroups();
    axios
      .get(`${webServices.baseURL}/profile/me`, {
        headers: { "Content-Type": "application/json" },
      })
      .then(({ data }) => {
        if (data.response.api_status) {
          this.user = data.response.profile;
          this.trading_info = data.response.trading_info;
        }
      })
      .catch(() => {
        Vue.notify({
          group: "signals",
          type: "error",
          text: "Can't load information!",
        });
      })
      .finally(() => {
        this.loading = false;
        Nprogress.done();
      });
    let instance = axios.create();
    delete instance.defaults.headers.common["Authorization"];
    instance
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
  methods: {
    ...mapActions(["changeAvatarAction", "getGroups"]),
    ...{
      getDateFormat(date) {
        if (!date) return "";
        return dateformat(new Date(date), "mmm, dd yyyy HH:MM");
      },
      getDateFormatWithMS(date) {
        if (!date) return "";
        date = parseInt(date);
        return dateformat(date, "mmm, dd yyyy HH:MM");
      },
      onEditUser() {
        this.openDialog();
      },
      editUser() {
        if (this.$refs.form.validate()) {
          const {
            name,
            email,
            isCanContact,
            experienceYear,
            experienceHistory,
            phone,
            password,
            country,
            address,
            zipcode,
          } = this.form;
          let userdata = {
            name,
            email,
          };
          if (phone && phone != "") {
            userdata = { ...userdata, ...{ phone } };
          }
          if (password && password != "") {
            userdata = { ...userdata, ...{ password } };
          }
          if (address && address != "") {
            userdata = { ...userdata, address };
          }
          if (country && country != "") {
            userdata = {
              ...userdata,
              country: this.countryList[country].name,
              flag: this.countryList[country].flag,
            };
          }
          if (zipcode && zipcode != "") {
            userdata = { ...userdata, zipcode };
          }
          userdata = {
            ...userdata,
            isCanContact,
            experienceYear,
            experienceHistory,
          };
          Nprogress.start();
          axios
            .patch(`${webServices.baseURL}/profile/me`, userdata)
            .then(() => {
              this.user = {
                ...this.user,
                ...userdata,
                experience_year: userdata.experienceYear,
                experience_history: userdata.experienceHistory,
              };
              Vue.notify({
                group: "signals",
                type: "success",
                text: "Profile Updated!",
              });
            })
            .catch(() => {
              Vue.notify({
                group: "signals",
                type: "error",
                text: "Update failed",
              });
            })
            .finally(() => {
              this.open = false;
              Nprogress.done();
            });
        }
      },
      changeAvatar(event) {
        var files = event.target.files || event.dataTransfer.files;
        if (!files.length) return;
        var formData = new FormData();
        formData.append("avatar", files[0]);
        Nprogress.start();
        axios
          .post(`${webServices.baseURL}/profile/avatar`, formData, {
            headers: {
              "Content-Type": "multipart/form-data",
            },
          })
          .then(({ data }) => {
            Vue.notify({
              group: "signals",
              type: "success",
              text: "Successfully Updated!",
            });
            this.user.avatar = data.response.filename;
            this.changeAvatarAction(data.response.filename);
          })
          .catch((error) => {
            console.log(error);
            Vue.notify({
              group: "signals",
              type: "error",
              text: "Upload failed",
            });
          })
          .finally(() => {
            Nprogress.done();
          });
      },
      openDialog() {
        this.form.name = this.user.name;
        this.form.email = this.user.email;
        this.form.isCanContact = this.user.isCanContact;
        this.form.experienceYear = this.user.experience_year;
        this.form.experienceHistory = this.user.experience_history;
        this.form.phone = this.user.phone;
        this.form.address = this.user.address;
        this.form.zipcode = this.user.zipcode;
        this.form.country = this.countryList.findIndex(
          (list) => list.name === this.user.country
        );
        this.form.country = this.form.country >= 0 ? this.form.country : "";
        this.form.password = this.user.password;

        this.open = true;
      },
    },
  },
  computed: {
    ...mapGetters(["groups"]),
    groupItems() {
      return this.groups.map((group) => ({
        value: group.id.toString(),
        text: group.name,
      }));
    },
    groupLabel() {
      let groupItem = this.groupItems.filter(
        (item) => item.value == this.user.group
      );
      if (groupItem.length) {
        return groupItem[0].text;
      }
      return "";
    },
  },
};
</script>