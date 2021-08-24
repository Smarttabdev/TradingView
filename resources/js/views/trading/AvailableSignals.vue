<template>
  <div>
    <page-title-bar></page-title-bar>
    <app-section-loader :status="availableSignal_loading"></app-section-loader>
    <v-container fluid class="grid-list-xl pt-0 mt-n3">
      <v-row>
        <app-card
          :fullBlock="true"
          colClasses="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12"
        >
          <v-row class="align-items-center search-wrap">
            <v-col cols="12" md="12" lg="12" class="align-items-center pt-0">
              <app-card customClasses="mb-0 pt-8">
                <v-row>
                  <v-col cols="12" md="12" lg="12" class="pb-0">
                    <div class="d-flex">
                      <div class="w-75">
                        <v-text-field
                          class="pt-0 pr-3"
                          label="Search Signal"
                          v-model="search"
                        >
                        </v-text-field>
                      </div>
                      <div>
                        <v-btn
                          color="primary"
                          class="my-0 ml-0 mr-2"
                          medium
                          @click="handleSearch"
                        >
                          <i class="material-icons">search</i>&nbsp;&nbsp;Search
                        </v-btn>
                        <input
                          type="file"
                          ref="loadData"
                          accept=".csv"
                          style="display: none"
                          @change="handleLoadData"
                        />
                      </div>
                    </div>
                  </v-col>
                </v-row>
              </app-card>
            </v-col>
          </v-row>
          <v-data-table
            :key="tableProvideKey"
            :mobile-breakpoint="0"
            :headers="headers2"
            :items="availableSignal_data"
            :search="search"
            item-key="account_number"
            :server-items-length="availableSignal_total"
            :options.sync="options"
            :loading="availableSignal_loading"
            :footer-props="{
              showFirstLastPage: true,
              itemsPerPageOptions: [5, 10, 15, 20],
            }"
            class="leaderboard-table"
          >
            <!-- <template slot="headerCell" slot-scope="props">
              <v-tooltip bottom>
                <template v-slot:activator="{ on }">
                  <span v-on="on">
                    {{ props.header.text }}
                  </span>
                </template>
                <span>
                  {{ props.header.text }}
                </span>
              </v-tooltip>
            </template> -->
            <template
              v-if="selected_strategy && selected_strategy.id"
              v-slot:body.prepend="{ headers }"
            >
              <div
                class="featured-ribbon"
                style="border-color: red !important; color: black !important"
              >
                <i
                  class="zmdi zmdi-star-circle"
                  style="color: red !important"
                ></i>
                Selected Strategy of Month
              </div>
              <tr
                class="featured-strategy"
                style="background: #004373 !important"
              >
                <td><i class="zmdi zmdi-thumb-up"></i></td>
                <td v-if="isAdmin">
                  <v-checkbox
                    color="primary"
                    input-value="true"
                    @change="
                      (evt) => setStrategyOfMonth(evt, selected_strategy.id)
                    "
                  ></v-checkbox>
                </td>
                <td
                  @click="showUserDetails(selected_strategy)"
                  style="cursor: pointer"
                >
                  <div class="provider-info">
                    <img
                      class="avatar-img"
                      :src="
                        selected_strategy.avatar
                          ? selected_strategy.avatar
                          : '/static/avatars/default.png'
                      "
                      width="40"
                      height="40"
                      alt=""
                    />
                    <div>
                      <h5>{{ selected_strategy.strategy_name }}</h5>
                      <v-tooltip bottom>
                        <template v-slot:activator="{ on }">
                          <img
                            v-on="on"
                            :src="selected_strategy.flag"
                            alt=""
                            width="28"
                          />
                        </template>
                        <span>{{ selected_strategy.country }}</span>
                      </v-tooltip>
                      &nbsp;
                      {{ selected_strategy.provider }}
                    </div>
                  </div>
                </td>
                <td>
                  <!-- <v-tooltip bottom v-if="selected_strategy.description">
                    <template v-slot:activator="{ on }">
                      <div
                        v-on="on"
                        @click="
                          descriptionShowMore(
                            selected_strategy.description,
                            selected_strategy.strategy_name
                          )
                        "
                        class="truncate"
                        style="cursor: pointer"
                      >
                        {{ selected_strategy.description }}
                      </div>
                    </template>
                    <span>Read more</span>
                  </v-tooltip> -->
                  £ {{ selected_strategy.subscription_fee || "-" }}
                </td>
                <td>{{ selected_strategy.performance_fee || "-" }} %</td>
                <td>{{ selected_strategy.inception_date }}</td>
                <td>
                  <line-chart-with-area
                    :dataSet="equityData(selected_strategy.id)"
                    :lineTension="0.5"
                    :width="200"
                    :height="50"
                    :enableGradient="true"
                    :enableXAxesLine="false"
                    color="#fff158"
                    :dataLabels="equityData(selected_strategy.id)"
                  >
                  </line-chart-with-area>
                </td>
                <td
                  :style="
                    'color:' +
                    stateColor[Number(dateToYear(selected_strategy.id) > 0)] +
                    ' !important'
                  "
                >
                  {{ dateToYear(selected_strategy.id) }} %
                </td>
                <td
                  :style="
                    'color:' +
                    stateColor[Number(dateToMonth(selected_strategy.id) > 0)] +
                    ' !important'
                  "
                >
                  {{ dateToMonth(selected_strategy.id) }} %
                </td>
                <td
                  :style="
                    'color:' +
                    stateColor[Number(lastMonth(selected_strategy.id) > 0)] +
                    ' !important'
                  "
                >
                  {{ lastMonth(selected_strategy.id) }} %
                </td>
                <td
                  :style="
                    'color:' +
                    stateColor[Number(drawdown(selected_strategy.id) > 0)] +
                    ' !important'
                  "
                >
                  {{ drawdown(selected_strategy.id) }} %
                </td>
                <td>{{ "" }}</td>
                <td>£ {{ selected_strategy.miniSize || "-" }}</td>
                <td>{{ selected_strategy.experience_year || 0 }}</td>
                <td>{{ accountType(selected_strategy.account_type) }}</td>
                <td>
                  <v-tooltip v-if="isAdmin" bottom>
                    <template v-slot:activator="{ on }">
                      <v-btn
                        v-on="on"
                        text
                        icon
                        color="primary"
                        @click="selectData(selected_strategy.id)"
                      >
                        <v-icon class="zmdi zmdi-upload"></v-icon>
                      </v-btn>
                    </template>
                    <span>Upload CSV data.</span>
                  </v-tooltip>
                  <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                      <v-btn
                        v-on="on"
                        text
                        icon
                        color="primary"
                        @click="
                          gotoDetail(
                            selected_strategy.account_number,
                            selected_strategy.broker
                          )
                        "
                      >
                        <v-icon class="zmdi zmdi-eye"></v-icon>
                      </v-btn>
                    </template>
                    <span>View Source Detail</span>
                  </v-tooltip>
                  <v-tooltip bottom v-if="canCopy(selected_strategy.group)">
                    <template v-slot:activator="{ on }">
                      <v-btn
                        v-on="on"
                        text
                        icon
                        color="success"
                        @click="
                          tryCopyAccount(
                            selected_strategy.broker,
                            selected_strategy.account_number
                          )
                        "
                      >
                        <v-icon class="zmdi zmdi-copy"></v-icon>
                      </v-btn>
                    </template>
                    <span>Copy This Source</span>
                  </v-tooltip>
                </td>
              </tr>
            </template>
            <template v-slot:item="props">
              <tr>
                <td>{{ props.index + 1 }}</td>
                <td v-if="isAdmin">
                  <v-checkbox
                    color="primary"
                    @change="(evt) => setStrategyOfMonth(evt, props.item.id)"
                  ></v-checkbox>
                </td>
                <td
                  @click="showUserDetails(props.item)"
                  style="cursor: pointer"
                >
                  <div class="provider-info">
                    <img
                      class="avatar-img"
                      :src="
                        props.item.avatar
                          ? props.item.avatar
                          : '/static/avatars/default.png'
                      "
                      width="40"
                      height="40"
                      alt=""
                    />
                    <div>
                      <h5>{{ props.item.strategy_name }}</h5>
                      <v-tooltip bottom>
                        <template v-slot:activator="{ on }">
                          <img
                            v-on="on"
                            :src="props.item.flag"
                            alt=""
                            width="28"
                          />
                        </template>
                        <span>{{ props.item.country }}</span>
                      </v-tooltip>
                      &nbsp;
                      {{ props.item.provider }}
                    </div>
                  </div>
                </td>
                <td>
                  <!-- <v-tooltip bottom v-if="props.item.description">
                    <template v-slot:activator="{ on }">
                      <div
                        v-on="on"
                        @click="
                          descriptionShowMore(
                            props.item.description,
                            props.item.strategy_name
                          )
                        "
                        class="truncate"
                        style="cursor: pointer"
                      >
                        {{ props.item.description }}
                      </div>
                    </template>
                    <span>Read more</span>
                  </v-tooltip> -->
                  £ {{ props.item.subscription_fee || "-" }}
                </td>
                <td>{{ props.item.performance_fee || "-" }} %</td>
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
                <td>{{ "" }}</td>
                <td>£ {{ props.item.miniSize || "-" }}</td>
                <td>{{ props.item.experience_year }}</td>
                <td>{{ accountType(props.item.account_type) }}</td>
                <td>
                  <v-tooltip v-if="isAdmin" bottom>
                    <template v-slot:activator="{ on }">
                      <v-btn
                        v-on="on"
                        text
                        icon
                        color="primary"
                        @click="selectData(props.item.id)"
                      >
                        <v-icon class="zmdi zmdi-upload"></v-icon>
                      </v-btn>
                    </template>
                    <span>Upload CSV data.</span>
                  </v-tooltip>
                  <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                      <v-btn
                        v-on="on"
                        text
                        icon
                        color="primary"
                        @click="
                          gotoDetail(
                            props.item.account_number,
                            props.item.broker
                          )
                        "
                      >
                        <v-icon class="zmdi zmdi-eye"></v-icon>
                      </v-btn>
                    </template>
                    <span>View Source Detail</span>
                  </v-tooltip>
                  <v-tooltip bottom v-if="canCopy(props.item.group)">
                    <template v-slot:activator="{ on }">
                      <v-btn
                        v-on="on"
                        text
                        icon
                        color="success"
                        @click="
                          tryCopyAccount(
                            props.item.broker,
                            props.item.account_number
                          )
                        "
                      >
                        <v-icon class="zmdi zmdi-copy"></v-icon>
                      </v-btn>
                    </template>
                    <span>Copy This Source</span>
                  </v-tooltip>
                </td>
              </tr>
            </template>
          </v-data-table>
        </app-card>
      </v-row>
    </v-container>

    <template>
      <v-dialog v-model="copyModal" max-width="800">
        <v-form ref="form" lazy-validation>
          <template>
            <v-card class="mx-auto px-6" v-if="source_account">
              <v-card-title
                class="title font-weight-regular justify-space-between"
              >
                <span
                  >Source from {{ source_account.account_number }} /
                  {{ source_account.broker }} will be copied to:</span
                >
                <v-avatar
                  color="primary lighten-2"
                  class="subheading white--text"
                  size="40"
                >
                  <v-icon class="zmdi zmdi-copy"></v-icon>
                </v-avatar>
              </v-card-title>
              <v-divider></v-divider>
              <v-row class="mt-5 mb-3">
                <v-col cols="12" sm="12" md="5" class="py-0">
                  <h4>My Available Accounts</h4>
                  <v-list
                    style="height: 300px; max-height: 300px"
                    class="mt-2 overflow-y-auto border"
                  >
                    <v-list-item-group
                      v-model="availableaccounts_selected"
                      multiple
                    >
                      <v-list-item
                        v-for="(account, index) in availableaccounts"
                        :key="index"
                      >
                        <template v-slot:default="{ active }">
                          <v-list-item-action>
                            <v-checkbox :input-value="active" color="primary">
                            </v-checkbox>
                          </v-list-item-action>
                          <v-list-item-content>
                            <v-list-item-title
                              >{{ account.account_number }}
                            </v-list-item-title>
                            <v-list-item-subtitle
                              >{{ account.broker }}
                            </v-list-item-subtitle>
                          </v-list-item-content>
                        </template>
                      </v-list-item>
                    </v-list-item-group>
                  </v-list>
                </v-col>
                <v-col
                  cols="12"
                  sm="12"
                  md="2"
                  class="d-flex align-center py-0"
                >
                  <div>
                    <v-btn
                      class="mt-3"
                      fab
                      dark
                      small
                      color="pink"
                      @click="addToCopy"
                    >
                      <v-icon>mdi-chevron-right</v-icon>
                    </v-btn>
                    <v-btn
                      class="mt-3"
                      fab
                      dark
                      small
                      color="pink"
                      @click="addToCopyAll"
                    >
                      <v-icon>mdi-chevron-double-right</v-icon>
                    </v-btn>
                    <v-btn
                      class="mt-3"
                      fab
                      dark
                      small
                      color="pink"
                      @click="removeFromCopy"
                    >
                      <v-icon>mdi-chevron-left</v-icon>
                    </v-btn>
                    <v-btn
                      class="mt-3"
                      fab
                      dark
                      small
                      color="pink"
                      @click="removeFromCopyAll"
                    >
                      <v-icon>mdi-chevron-double-left</v-icon>
                    </v-btn>
                  </div>
                </v-col>
                <v-col cols="12" sm="12" md="5" class="py-0">
                  <h4>Selected Accounts</h4>
                  <v-list
                    style="height: 300px; max-height: 300px"
                    class="mt-2 overflow-y-auto border"
                  >
                    <v-list-item-group
                      v-model="copyingaccounts_selected"
                      multiple
                    >
                      <v-list-item
                        v-for="(account, index) in copyingaccounts"
                        :key="index"
                      >
                        <template v-slot:default="{ active }">
                          <v-list-item-action>
                            <v-checkbox :input-value="active" color="primary">
                            </v-checkbox>
                          </v-list-item-action>
                          <v-list-item-content>
                            <v-list-item-title
                              >{{ account.account_number }}
                            </v-list-item-title>
                            <v-list-item-subtitle
                              >{{ account.broker }}
                            </v-list-item-subtitle>
                          </v-list-item-content>
                        </template>
                      </v-list-item>
                    </v-list-item-group>
                  </v-list>
                </v-col>
              </v-row>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                  :disabled="isFormSubmitting"
                  color="primary"
                  depressed
                  @click="copySource"
                >
                  Submit
                </v-btn>
              </v-card-actions>
            </v-card>
          </template>
        </v-form>
      </v-dialog>
    </template>
    <template>
      <v-dialog v-model="showUserDetailsDialog" max-width="800px">
        <v-card>
          <v-card-title class="headline grey lighten-2">
            User Profile
          </v-card-title>

          <v-card-text>
            <br />
            <v-row>
              <v-col cols="5">
                <v-img
                  class="mx-auto"
                  :src="
                    selectedStrategy.avatar
                      ? selectedStrategy.avatar
                      : '/static/avatars/default.png'
                  "
                  alt="user"
                  width="130px"
                  height="130px"
                ></v-img>
              </v-col>
              <v-col cols="7">
                <h4>Name : {{ selectedStrategy.provider }}</h4>
                <h5>
                  Email :
                  {{
                    selectedStrategy.isCanContact
                      ? selectedStrategy.email
                      : "---------@----"
                  }}
                </h5>
                <h5>
                  Group Assigned To : {{ groupLabel(selectedStrategy.group) }}
                </h5>
                <h5>
                  Experience : {{ selectedStrategy.experience_year }} (years)
                </h5>
                <h5>History : {{ selectedStrategy.experience_history }}</h5>
              </v-col>
              <v-col class="12">
                <v-tabs>
                  <v-tab href="#description">Description</v-tab>
                  <v-tab href="#strategies">More Strategies</v-tab>
                  <v-tab-item value="description">
                    <v-card flat tile>
                      <v-card-text>
                        <v-row>
                          <v-col cols="6">
                            <h5>
                              Strage Name : {{ selectedStrategy.strategy_name }}
                            </h5>
                          </v-col>
                          <v-col cols="6">
                            <h5>
                              Trading Style :
                              {{ selectedStrategy.trading_style }}
                            </h5>
                          </v-col>
                          <v-col cols="12">
                            <h6>Description :</h6>
                            <p>{{ selectedStrategy.description }}</p>
                          </v-col>
                        </v-row>
                      </v-card-text>
                    </v-card>
                  </v-tab-item>
                  <v-tab-item value="strategies">
                    <v-card flat tile>
                      <v-card-text class="pb-0">
                        <v-data-table
                          :headers="headers1"
                          :mobile-breakpoint="0"
                          :items="useravailableSignal_data"
                          :search="search"
                          item-key="email"
                          :server-items-length="useravailableSignal_total"
                          :options.sync="userOptions"
                          :loading="useravailableSignal_loading"
                          :items-per-page="3"
                          :footer-props="{
                            showFirstLastPage: true,
                            itemsPerPageOptions: [3, 6, 9, 15],
                          }"
                          class="elevation-1"
                        ></v-data-table>
                      </v-card-text>
                    </v-card>
                  </v-tab-item>
                </v-tabs>
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
import Vue from "vue";
import { mapGetters, mapActions } from "vuex";
import axios from "axios";
// import LineChartShadowV2 from "Components/Charts/LineChartShadowV2";
import LineChartWithArea from "Components/Charts/LineChartWithArea";
import dateformat from "dateformat";
import webServices from "WebServices";
import Nprogress from "nprogress";

export default {
  components: {
    // LineChartShadowV2,
    LineChartWithArea,
  },
  data() {
    return {
      headers1: [
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
      ],
      my_strategy: [],
      onTimeDialog: false,
      offTimeDialog: false,
      availableaccounts: [],
      copyingaccounts: [],
      availableaccounts_selected: [],
      copyingaccounts_selected: [],
      search: "",
      selectId: -1,
      showUserDetailsDialog: false,
      description: "",
      strategy_name: "",
      selectedStrategy: "",
      headers: [
        {
          text: "#",
          align: "left",
          sortable: false,
        },
        {
          text: "",
          sortable: false,
        },
        { text: "Strategy Name", value: "strategy_name", sortable: true },
        { text: "Subscription Fee", value: "subscription_fee", sortable: true },
        { text: "Performance Fee", value: "performance_fee", sortable: true },
        {
          text: "Strategy Inception Date",
          value: "inception_date",
          sortable: true,
        },
        { text: "Equity Curve", sortable: false },
        { text: "Year to Date Return", value: "year", sortable: true },
        {
          text: "Month to Date Return",
          value: "month",
          sortable: true,
        },
        { text: "Last Month Return", value: "lastMonth", sortable: true },
        { text: "Max Drawdown", value: "max_drawdown", sortable: true },
        { text: "Sharpe Ratio", sortable: false },
        {
          text: "Suggested Minimum Account Size",
          value: "miniSize",
          sortable: true,
        },
        {
          text: "Manager Experience",
          value: "experience_year",
          sortable: true,
        },
        { text: "Account Type", sortable: false },
        { text: "Action", sortable: false },
      ],
      options: {},
      userOptions: { itemsPerPage: 3 },
      delete_id: null,
      tableProvideKey: 0,
      copyModal: false,
      source_account: null,
      accounts: [],
      isFormSubmitting: false,
      equityChart: {
        duration: "last 4 days",
        market_cap: "2.3",
        market_cap_icon: "fa-arrow-up",
        market_cap_color: "success-text",
        data: [10, 26, 8, 22, 24, 25, 21, 15, 30],
        color: "#9ce167",
      },
      stateColor: ["#ff8686", "#c5ffc5"],
    };
  },
  mounted() {
    axios
      .get(`${webServices.baseURL}/accounts-for-copy`, {
        headers: { "Content-Type": "application/json" },
      })
      .then(({ data }) => {
        const { api_status, accounts } = data.response;
        if (api_status) {
          this.accounts = accounts;
        }
      })
      .catch(() => {
        Vue.notify({
          group: "signals",
          type: "error",
          text: "Loading accounts failed!",
        });
      });
    this.getGroups();
  },
  methods: {
    ...mapActions([
      "getAvailableSignalAction",
      "getUserAvailableSignalAction",
      "getGroups",
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
      gotoDetail(account_number, broker) {
        this.$router.push({
          path: `signal-detail/${account_number}/${broker}`,
        });
      },
      tryCopyAccount(broker, account_number) {
        this.copyModal = true;

        this.availableaccounts = [...this.accounts];
        this.availableaccounts_selected = [];

        this.copyingaccounts = [];
        this.copyingaccounts_selected = [];

        this.source_account = { broker, account_number };
      },
      addToCopy() {
        if (this.availableaccounts_selected.length == 0) return;
        this.availableaccounts_selected.map((selected) => {
          this.copyingaccounts.push(this.availableaccounts[selected]);
        });

        const newvals = this.availableaccounts.filter((account, index) => {
          return (
            this.availableaccounts_selected.find(
              (selected) => selected == index
            ) === undefined
          );
        });
        this.availableaccounts_selected = [];
        this.availableaccounts = newvals;
        this.copyingaccounts_selected = [];
      },
      addToCopyAll() {
        this.availableaccounts = [];
        this.availableaccounts_selected = [];
        this.copyingaccounts = [...this.accounts];
        this.copyingaccounts_selected = [];
      },
      removeFromCopy() {
        if (this.copyingaccounts_selected.length == 0) return;
        this.copyingaccounts_selected.map((selected) => {
          this.availableaccounts.push(this.copyingaccounts[selected]);
        });

        const newvals = this.copyingaccounts.filter((account, index) => {
          return (
            this.copyingaccounts_selected.find(
              (selected) => selected == index
            ) === undefined
          );
        });
        this.copyingaccounts_selected = [];
        this.copyingaccounts = newvals;
        this.availableaccounts_selected = [];
      },
      removeFromCopyAll() {
        this.availableaccounts = [...this.accounts];
        this.availableaccounts_selected = [];
        this.copyingaccounts = [];
        this.copyingaccounts_selected = [];
      },
      copySource() {
        this.isFormSubmitting = true;
        Nprogress.start();

        axios
          .post(
            `${webServices.baseURL}/copysources`,
            {
              source_account: this.source_account,
              accounts: this.copyingaccounts,
            },
            { headers: { "Content-Type": "application/json" } }
          )
          .then(({ data }) => {
            const { api_status, message } = data.response;

            if (api_status) {
              this.tableProvideKey++;
              Vue.notify({
                group: "signals",
                type: "success",
                text: message,
              });
            } else {
              Vue.notify({
                group: "signals",
                type: "error",
                text: message,
              });
            }
          })
          .catch((error) => {
            let message = "Copying signal failed.";
            Vue.notify({
              group: "signals",
              type: "error",
              text: message,
            });
          })
          .finally(() => {
            this.copyModal = false;
            this.copied_account = [];
            this.isFormSubmitting = false;
            Nprogress.done();
          });
      },
      setStrategyOfMonth(evt, acc_id) {
        // console.log(evt, acc_id);
        // this.isFormSubmitting = true;
        Nprogress.start();

        axios
          .post(
            `${webServices.baseURL}/accounts/setStrategyOfMonth/${acc_id}`,
            {
              strategy_of_month: Number(evt),
            },
            { headers: { "Content-Type": "application/json" } }
          )
          .then(({ data }) => {
            const { api_status, message } = data.response;

            if (api_status) {
              this.tableProvideKey++;
              Vue.notify({
                group: "signals",
                type: "success",
                text: message,
              });
            } else {
              Vue.notify({
                group: "signals",
                type: "error",
                text: message,
              });
            }
          })
          .catch((error) => {
            let message = "Setting failed.";
            Vue.notify({
              group: "signals",
              type: "error",
              text: message,
            });
          })
          .finally(() => {
            // this.getAvailableSignalAction({
            //   page: this.options.page,
            //   perPage: this.options.itemsPerPage,
            //   search: this.search,
            // });
            Nprogress.done();
          });
      },
    },
    showUserDetails(item) {
      this.selectedStrategy = item;
      // console.log(item);
      this.showUserDetailsDialog = true;
    },
    handleSearch() {
      const { page, itemsPerPage, sortBy, sortDesc } = this.options;

      this.getAvailableSignalAction({
        page: page,
        perPage: itemsPerPage,
        sortBy: sortBy.length ? sortBy[0] : undefined,
        dir: sortDesc.length ? (sortDesc[0] ? "asc" : "desc") : undefined,
        search: this.search,
      });
    },
    handleLoadData(e) {
      var files = e.target.files || e.dataTransfer.files;
      if (!files.length) return;
      console.log(files[0].type);
      if (files[0].type !== "application/vnd.ms-excel") {
        Vue.notify({
          group: "signals",
          type: "warning",
          text: "Please select CSV file!",
        });
        return;
      }
      var formData = new FormData();
      formData.append("data", files[0]);
      Nprogress.start();
      axios
        .post(
          `${webServices.baseURL}/availablesources/upload/${this.selectId}`,
          formData,
          {
            headers: {
              "Content-Type": "multipart/form-data",
            },
          }
        )
        .then(({ data }) => {
          Vue.notify({
            group: "signals",
            type: "success",
            text: "Successfully Loaded!",
          });
          // this.tableProvideKey++;
          this.getAvailableSignalAction({
            page: this.options.page,
            perPage: this.options.itemsPerPage,
            sortBy: this.options.sortBy.length
              ? this.options.sortBy[0]
              : undefined,
            dir: this.options.sortDesc.length
              ? sthis.options.ortDesc[0]
                ? "asc"
                : "desc"
              : undefined,
            search: this.search,
          });
          // this.user.avatar = data.response.filename;
        })
        .catch(() => {
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

    selectData(id) {
      this.selectId = id;
      this.$refs.loadData.click();
    },
  },
  computed: {
    ...mapGetters([
      "availableSignal_data",
      "selected_strategy",
      "analyze_data",
      "availableSignal_perPage",
      "availableSignal_total",
      "availableSignal_page",
      "availableSignal_loading",
      "useravailableSignal_data",
      "useranalyze_data",
      "useravailableSignal_perPage",
      "useravailableSignal_total",
      "useravailableSignal_page",
      "useravailableSignal_loading",
      "getUser",
      "groups",
    ]),
    canCopy() {
      return (group) => {
        if (!this.getUser) return false;
        if (
          this.getUser.group.canCopy?.split(",").includes(group.toString()) ||
          this.getUser.group?.id == group
        ) {
          return true;
        }
        return false;
      };
    },
    equityData() {
      return (acc_id) => {
        let equity = this.analyze_data[acc_id]?.equity.map(
          (data) => data.equity
        );
        return equity;
      };
    },
    // equity() {
    //   return (acc_id) => {
    //     return this.analyze_data[acc_id].equity[
    //       this.analyze_data[acc_id].length
    //     ].equity;
    //   };
    // },
    // balance() {
    //   return (acc_id) => {
    //     return this.analyze_data[acc_id].equity[
    //       this.analyze_data[acc_id].length
    //     ].balance;
    //   };
    // },
    dateToMonth() {
      return (acc_id) => {
        if (!this.analyze_data[acc_id].month) return 0;
        return Number(this.analyze_data[acc_id].month).toFixed(1);
      };
    },
    dateToYear() {
      return (acc_id) => {
        if (!this.analyze_data[acc_id].year) return 0;
        return Number(this.analyze_data[acc_id].year).toFixed(1);
      };
    },
    lastMonth() {
      return (acc_id) => {
        if (!this.analyze_data[acc_id].lastMonth) return 0;
        return Number(this.analyze_data[acc_id].lastMonth).toFixed(1);
      };
    },
    drawdown() {
      return (acc_id) => {
        // const equity = this.analyze_data[acc_id].equity;
        if (!this.analyze_data[acc_id].drawdown) {
          return 0;
        }
        return Number(this.analyze_data[acc_id].drawdown).toFixed(1);
        // if (!equity.length) return 0;
        // return (
        //   ((Number(equity[equity.length - 1].max_equity) -
        //     Number(equity[equity.length - 1].min_equity)) /
        //     Number(equity[equity.length - 1].max_equity)) *
        //   100
        // ).toFixed(1);
      };
    },

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
    accountType() {
      const account_types = ["Demo", "Contest", "Live"];
      return (type) => account_types[type];
    },
    isAdmin() {
      if (!this.getUser) return false;

      return this.getUser.roles[0] == "ROLE_ADMIN";
    },
    headers2() {
      if (this.isAdmin) {
        return this.headers;
      }
      let header = this.headers;
      header.splice(1, 1);
      return header;
    },
  },
  watch: {
    options: function (options) {
      // console.log(options, "-----options----");
      const { page, itemsPerPage, sortBy, sortDesc } = options;
      this.getAvailableSignalAction({
        page: page,
        perPage: itemsPerPage,
        sortBy: sortBy.length ? sortBy[0] : undefined,
        dir: sortDesc.length ? (sortDesc[0] ? "asc" : "desc") : undefined,
        search: this.search,
      });
    },
    userOptions: function (options) {
      this.getUserAvailableSignalAction({
        page: options.page,
        perPage: options.itemsPerPage,
        user_id: this.selectedStrategy.user_id,
      });
    },
    "selectedStrategy.user_id": function (id) {
      this.getUserAvailableSignalAction({
        page: this.options.page,
        perPage: this.options.itemsPerPage,
        sortBy: this.options.sortBy.length ? this.options.sortBy[0] : undefined,
        dir: this.options.sortDesc.length
          ? this.options.sortDesc[0]
            ? "asc"
            : "desc"
          : undefined,
        user_id: id,
      });
    },
    analyze_data() {
      console.log(this.analyze_data);
    },
    // selectedStrategy() {
    //   console.log(this.selectedStrategy);
    // },
    // selected_strategy() {
    //   console.log(this.selected_strategy, "---");
    // },
    // availableSignal_data() {
    //   console.log(this.availableSignal_data);
    // },
    equityData() {
      console.log(this.equityData);
    },
  },
};
</script>