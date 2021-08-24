<template>
  <div>
    <page-title-bar></page-title-bar>
    <app-section-loader :status="users_loading"></app-section-loader>
    <v-container fluid class="grid-list-xl pt-0 mt-n3">
      <v-row>
        <v-tabs
          class="reports-table-tab"
          v-model="active"
          slider-color="primary"
        >
          <v-tab class="text-capitalize" href="#existingusers">
            {{ $t("message.existingUsers") }}
          </v-tab>
          <v-tab class="text-capitalize" href="#newusers">
            {{ $t("message.newUsers") }}
          </v-tab>
          <v-tab-item value="existingusers">
            <app-card
              :fullBlock="true"
              colClasses="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12"
            >
              <v-data-table
                :mobile-breakpoint="0"
                :key="usersTableKey"
                :headers="headers"
                :items="users_data"
                :search="search"
                item-key="email"
                :server-items-length="users_total"
                :options.sync="options"
                :loading="users_loading"
                :footer-props="{
                  showFirstLastPage: true,
                  itemsPerPageOptions: [5, 10, 15, 20],
                }"
              >
                <template
                  slot="headerCell"
                  slot-scope="props"
                  loading-text="Loading... Please wait"
                >
                  <v-tooltip bottom>
                    <span slot="activator">
                      {{ props.header.text }}
                    </span>
                    <span>
                      {{ props.header.text }}
                    </span>
                  </v-tooltip>
                </template>
                <template v-slot:item="props">
                  <tr>
                    <td>{{ props.item.id }}</td>
                    <td
                      @click="showUserStrategy(props.item)"
                      style="cursor: pointer"
                    >
                      {{ props.item.name }}
                    </td>
                    <td>
                      <v-list-item-avatar>
                        <img
                          :src="
                            props.item.avatar
                              ? props.item.avatar
                              : '/static/avatars/default.png'
                          "
                          alt="avatar"
                          height="30"
                          width="30"
                          class="img-responsive"
                        />
                      </v-list-item-avatar>
                    </td>
                    <td>
                      {{ props.item.email }}
                    </td>
                    <td>
                      {{ groupLabel(props.item.group) }}
                    </td>
                    <td>
                      <v-tooltip bottom>
                        <template v-slot:activator="{ on }">
                          <v-img
                            v-on="on"
                            max-width="30"
                            :src="props.item.flag"
                          >
                          </v-img>
                        </template>
                        <span>{{ props.item.country }}</span>
                      </v-tooltip>
                    </td>
                    <td>{{ props.item.address }}</td>
                    <td>{{ props.item.zipcode }}</td>
                    <td>{{ props.item.phone }}</td>
                    <td>{{ getDateFormat(props.item.created_at) }}</td>
                    <td>
                      <router-link
                        :to="{
                          name: 'user-profile',
                          params: { user_id: props.item.id },
                        }"
                      >
                        <v-tooltip bottom>
                          <template v-slot:activator="{ on }">
                            <v-btn v-on="on" text icon color="primary">
                              <v-icon class="zmdi zmdi-eye"></v-icon>
                            </v-btn>
                          </template>
                          <span>View Profile of This User</span>
                        </v-tooltip>
                      </router-link>
                      <v-tooltip bottom>
                        <template v-slot:activator="{ on }">
                          <v-btn
                            v-on="on"
                            text
                            icon
                            color="error"
                            @click="deleteUser(props.item.id)"
                          >
                            <v-icon class="zmdi zmdi-delete"></v-icon>
                          </v-btn>
                        </template>
                        <span>Delete This User</span>
                      </v-tooltip>
                    </td>
                  </tr>
                </template>
              </v-data-table>
            </app-card>
          </v-tab-item>
          <v-tab-item value="newusers">
            <app-card
              :fullBlock="true"
              colClasses="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12"
            >
              <v-data-table
                :mobile-breakpoint="0"
                :key="newUsersTableKey"
                :headers="headers"
                :items="new_users_data"
                :search="search"
                item-key="email"
                :server-items-length="new_users_total"
                :options.sync="new_options"
                :loading="new_users_loading"
                :footer-props="{
                  showFirstLastPage: true,
                  itemsPerPageOptions: [5, 10, 15, 20],
                }"
              >
                <template
                  slot="headerCell"
                  slot-scope="props"
                  loading-text="Loading... Please wait"
                >
                  <v-tooltip bottom>
                    <span slot="activator">
                      {{ props.header.text }}
                    </span>
                    <span>
                      {{ props.header.text }}
                    </span>
                  </v-tooltip>
                </template>
                <template v-slot:item="props">
                  <tr>
                    <td>{{ props.item.id }}</td>
                    <td>{{ props.item.name }}</td>
                    <td>
                      <v-list-item-avatar>
                        <img
                          :src="
                            props.item.avatar
                              ? props.item.avatar
                              : '/static/avatars/default.png'
                          "
                          alt="avatar"
                          height="30"
                          width="30"
                          class="img-responsive"
                        />
                      </v-list-item-avatar>
                    </td>
                    <td>
                      <router-link
                        :to="{
                          name: 'user-profile',
                          params: { user_id: props.item.id },
                        }"
                      >
                        {{ props.item.email }}
                      </router-link>
                    </td>
                    <td>
                      <v-tooltip bottom>
                        <template v-slot:activator="{ on }">
                          <v-img
                            v-on="on"
                            max-width="30"
                            :src="props.item.flag"
                          >
                          </v-img>
                        </template>
                        <span>{{ props.item.country }}</span>
                      </v-tooltip>
                    </td>
                    <td>{{ props.item.address }}</td>
                    <td>{{ props.item.zipcode }}</td>
                    <td>{{ props.item.phone }}</td>
                    <td>{{ getDateFormat(props.item.created_at) }}</td>
                    <td>
                      <v-tooltip bottom>
                        <template v-slot:activator="{ on }">
                          <v-btn
                            v-on="on"
                            text
                            icon
                            color="success"
                            @click="settingUser(props.item.id)"
                          >
                            <v-icon class="zmdi zmdi-check-circle-u"></v-icon>
                          </v-btn>
                        </template>
                        <span>Active This User</span>
                      </v-tooltip>
                      <v-tooltip bottom>
                        <template v-slot:activator="{ on }">
                          <v-btn
                            v-on="on"
                            text
                            icon
                            color="red"
                            @click="blockUser(props.item.id)"
                          >
                            <v-icon class="zmdi zmdi-block-alt"></v-icon>
                          </v-btn>
                        </template>
                        <span>Decline</span>
                      </v-tooltip>
                    </td>
                  </tr>
                </template>
              </v-data-table>
            </app-card>
          </v-tab-item>
        </v-tabs>
      </v-row>
      <delete-confirmation-dialog
        ref="deleteConfirmationDialog"
        heading="Are You Sure You Want To Delete?"
        message="Are you sure you want to delete this User permanently?"
        @onConfirm="deleteUserConfirm"
      >
      </delete-confirmation-dialog>
      <confirmation-dialog
        ref="activeConfirmationDialog"
        heading="Are You Sure You Want To Activate?"
        message="Are you sure you want to active this User?"
        @onConfirm="activeUserConfirm"
        confirmColor="success"
      >
      </confirmation-dialog>
      <v-dialog persistent v-model="open" max-width="500px">
        <v-card>
          <v-card-title class="headline grey lighten-2">
            Group Settings
          </v-card-title>

          <v-card-text class="py-2">
            <v-form v-model="form.valid" ref="form" lazy-validation>
              <v-select
                :items="groupItems"
                label="Select Group"
                menu-props="auto"
                v-model="form.group"
                :rules="form.groupRules"
                required
                class="pb-2"
              ></v-select>
            </v-form>
          </v-card-text>

          <v-divider></v-divider>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="primary" @click="open = false"> Cancel </v-btn>
            <v-btn color="success" @click="activeUser"> Yes </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
      <confirmation-dialog
        ref="blockConfirmationDialog"
        heading="Are You Sure You Want To Block?"
        message="Are you sure you want to block this User?"
        @onConfirm="blockUserConfirm"
        confirmColor="red"
      >
      </confirmation-dialog>
    </v-container>
    <template>
      <v-dialog v-model="showUserDetailsDialog" max-width="1200px">
        <v-card>
          <v-card-title class="headline grey lighten-2">
            {{ selUser.name }} - User Strategy
          </v-card-title>

          <v-card-text>
            <br />
            <v-row>
              <v-col cols="12">
                <v-data-table
                  :mobile-breakpoint="0"
                  :headers="strategyHeaders"
                  :items="useravailableSignal_data"
                  :search="search"
                  item-key="email"
                  :server-items-length="useravailableSignal_total"
                  :options.sync="strategyOptions"
                  :loading="useravailableSignal_loading"
                  :items-per-page="5"
                  :footer-props="{
                    showFirstLastPage: true,
                    itemsPerPageOptions: [5, 10, 15, 20],
                  }"
                  class="elevation-1"
                >
                  <template v-slot:item="props">
                    <tr>
                      <td>{{ props.item.strategy_name }}</td>
                      <td>{{ props.item.trading_style }}</td>
                      <td>{{ props.item.inception_date }}</td>
                      <td>
                        <line-chart-with-area
                          :dataSet="equityData(props.item.id)"
                          :lineTension="0.5"
                          :width="200"
                          :height="50"
                          :enableGradient="true"
                          :enableXAxesLine="false"
                          :color="equityChart.color"
                          :dataLabels="equityData(props.item.id)"
                        >
                        </line-chart-with-area>
                      </td>
                      <td
                        :style="
                          'color:' +
                          stateColor[Number(dateToYear(props.item.id) > 0)] +
                          ' !important'
                        "
                      >
                        {{ dateToYear(props.item.id) }} %
                      </td>
                      <td
                        :style="
                          'color:' +
                          stateColor[Number(dateToMonth(props.item.id) > 0)] +
                          ' !important'
                        "
                      >
                        {{ dateToMonth(props.item.id) }} %
                      </td>
                      <td
                        :style="
                          'color:' +
                          stateColor[Number(lastMonth(props.item.id) > 0)] +
                          ' !important'
                        "
                      >
                        {{ lastMonth(props.item.id) }} %
                      </td>
                      <td
                        :style="
                          'color:' +
                          stateColor[Number(drawdown(props.item.id) > 0)] +
                          ' !important'
                        "
                      >
                        {{ drawdown(props.item.id) }} %
                      </td>
                    </tr>
                  </template>
                </v-data-table>
              </v-col>
            </v-row>
          </v-card-text>

          <v-divider></v-divider>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="error" @click="showUserDetailsDialog = false">
              Close
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </template>
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import dateformat from "dateformat";
import axios from "axios";
import webServices from "WebServices";
import Vue from "vue";
import LineChartWithArea from "Components/Charts/LineChartWithArea";

export default {
  components: {
    LineChartWithArea,
  },
  data() {
    return {
      usersTableKey: 0,
      newUsersTableKey: 0,
      showUserDetailsDialog: false,
      open: false,
      selUser: {},
      search: "",
      equityChart: {
        duration: "last 4 days",
        market_cap: "2.3",
        market_cap_icon: "fa-arrow-up",
        market_cap_color: "success-text",
        data: [10, 26, 8, 22, 24, 25, 21, 15, 30],
        color: "#9ce167",
      },
      stateColor: ["#ff8686", "#c5ffc5"],
      headers: [
        {
          text: "ID",
          align: "left",
          sortable: true,
          value: "id",
        },
        { text: "Name", value: "name", sortable: true },
        { text: "Avatar", value: "avatar", sortable: false },
        { text: "Email", value: "email", sortable: false },
        { text: "Group", value: "group", sortable: true },
        { text: "Country", value: "country", sortable: false },
        { text: "Address", value: "address", sortable: false },
        { text: "Zip Code", value: "zipcode", sortable: false },
        { text: "Phone", value: "phone", sortable: false },
        { text: "Joined In", value: "created_at", sortable: false },
        { text: "", sortable: false },
      ],
      strategyHeaders: [
        // {
        //   text: "#",
        //   align: "left",
        //   sortable: false,
        // },
        {
          text: "Strategy Name",
          align: "start",
          sortable: false,
          value: "strategy_name",
        },
        {
          text: "Trading Style",
          align: "start",
          sortable: false,
          value: "trading_style",
        },
        {
          text: "Strategy Inception Date",
          value: "inception_date",
          sortable: false,
        },
        { text: "Equity Curve", sortable: false },
        { text: "Year to Date Return", value: "yearToReturn", sortable: false },
        {
          text: "Month to Date Return",
          value: "monthToReturn",
          sortable: false,
        },
        { text: "Last Month Return", value: "lastMonth", sortable: false },
        { text: "Max Drawdown", value: "max_drawdown", sortable: false },
      ],
      options: {},
      new_options: {},
      strategyOptions: { itemsPerPage: 5 },
      active: "existingusers",
      delete_id: null,
      active_id: null,
      block_id: null,
      form: {
        valid: true,
        group: "",
        groupRules: [(v) => !!v || "Please select Group"],
      },
    };
  },
  mounted() {
    this.getGroups();
  },
  methods: {
    ...mapActions([
      "getUsersAction",
      "getNewUsersAction",
      "getGroups",
      "getUserAvailableSignalAction",
    ]),
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
      deleteUser(id) {
        this.delete_id = id;
        this.$refs.deleteConfirmationDialog.openDialog();
      },
      deleteUserConfirm() {
        axios
          .delete(`${webServices.baseURL}/users/${this.delete_id}`)
          .then(({ data }) => {
            Vue.notify({
              group: "signals",
              type: "success",
              text: "User deleted!",
            });
            this.usersTableKey++;
          })
          .catch(() => {
            Vue.notify({
              group: "signals",
              type: "error",
              text: "Deletion failed!",
            });
          })
          .finally(() => {
            this.$refs.deleteConfirmationDialog.close();
          });
      },
      activeUser() {
        if (this.$refs.form.validate()) {
          this.open = false;
          this.$refs.activeConfirmationDialog.openDialog();
        }
      },
      settingUser(id) {
        this.active_id = id;
        this.open = true;
        // this.open = true;
      },
      activeUserConfirm() {
        axios
          .patch(`${webServices.baseURL}/users/${this.active_id}`, {
            active: 1,
            group: this.form.group,
          })
          .then(() => {
            Vue.notify({
              group: "signals",
              type: "success",
              text: "User activated!",
            });
            this.usersTableKey++;
            this.newUsersTableKey++;
          })
          .catch(() => {
            Vue.notify({
              group: "signals",
              type: "error",
              text: "Activation failed!",
            });
          })
          .finally(() => {
            this.$refs.activeConfirmationDialog.close();
          });
      },
      blockUser(id) {
        this.block_id = id;
        this.$refs.blockConfirmationDialog.openDialog();
      },
      blockUserConfirm() {
        axios
          .patch(`${webServices.baseURL}/users/${this.block_id}`, {
            active: -1,
          })
          .then(() => {
            Vue.notify({
              group: "signals",
              type: "success",
              text: "User blocked!",
            });
            this.newUsersTableKey++;
          })
          .catch(() => {
            Vue.notify({
              group: "signals",
              type: "error",
              text: "Block failed!",
            });
          })
          .finally(() => {
            this.$refs.blockConfirmationDialog.close();
          });
      },
      showUserStrategy(user) {
        this.selUser = user;
        this.showUserDetailsDialog = true;
      },
    },
  },
  computed: {
    ...mapGetters([
      "users_data",
      "users_perPage",
      "users_total",
      "users_page",
      "users_loading",
      "new_users_data",
      "new_users_perPage",
      "new_users_total",
      "new_users_page",
      "new_users_loading",
      "groups",
      "useravailableSignal_data",
      "useranalyze_data",
      "useravailableSignal_perPage",
      "useravailableSignal_total",
      "useravailableSignal_page",
      "useravailableSignal_loading",
    ]),

    groupItems() {
      return this.groups.map((group) => ({
        value: group.id.toString(),
        text: group.name,
      }));
    },

    groupLabel() {
      return (groupId) => {
        let groupItem = this.groupItems.filter((item) => item.value == groupId);
        if (groupItem.length) {
          return groupItem[0].text;
        }
        return "";
      };
    },

    equityData() {
      return (acc_id) => {
        return this.useranalyze_data[acc_id]?.equity.map((data) => data.equity);
      };
    },

    dateToMonth() {
      return (acc_id) => {
        if (!this.useranalyze_data[acc_id].equity.length) return 0;
        return Number(this.useranalyze_data[acc_id].month).toFixed(1);
      };
    },

    dateToYear() {
      return (acc_id) => {
        if (!this.useranalyze_data[acc_id].equity.length) return 0;
        return Number(this.useranalyze_data[acc_id].year).toFixed(1);
      };
    },

    lastMonth() {
      return (acc_id) => {
        if (!this.useranalyze_data[acc_id].lastMonth) return 0;
        return Number(this.useranalyze_data[acc_id].lastMonth).toFixed(1);
      };
    },

    drawdown() {
      return (acc_id) => {
        if (!this.useranalyze_data[acc_id].drawdown) {
          return 0;
        }
        return Number(this.useranalyze_data[acc_id].drawdown).toFixed(1);
      };
    },
  },

  watch: {
    options: function (options) {
      const { page, itemsPerPage, sortBy, sortDesc } = options;
      this.getUsersAction({
        page: page,
        perPage: itemsPerPage,
        sortBy: sortBy.length ? sortBy[0] : undefined,
        dir: sortDesc.length ? (sortDesc[0] ? "asc" : "desc") : undefined,
      });
    },
    new_options: function (options) {
      const { page, itemsPerPage, sortBy, sortDesc } = options;
      this.getNewUsersAction({
        page: page,
        perPage: itemsPerPage,
        sortBy: sortBy.length ? sortBy[0] : undefined,
        dir: sortDesc.length ? (sortDesc[0] ? "desc" : "asc") : undefined,
      });
    },
    strategyOptions: function (options) {
      this.getUserAvailableSignalAction({
        page: options.page,
        perPage: options.itemsPerPage,
        user_id: this.selUser.id,
      });
    },
    "selUser.id": function (id) {
      console.log(id);
      this.getUserAvailableSignalAction({
        page: this.strategyOptions.page,
        perPage: this.strategyOptions.itemsPerPage,
        user_id: id,
      });
    },
    useravailableSignal_data: function (data) {
      console.log(data);
    },
  },
};
</script>
