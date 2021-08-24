<template>
  <div>
    <page-title-bar></page-title-bar>
    <app-section-loader :status="settings_loading"></app-section-loader>
    <v-container fluid class="grid-list-xl pt-0 mt-n3">
      <v-row>
        <v-col cols="12" sm="6" md="4" lg="3">
          <v-card class="elevation-5 px-3 py-3">
            <v-card-title>
              <h3 class="headline primary--text">Logo Image Setting</h3>
            </v-card-title>
            <img
              :src="appLogo"
              alt="Card Image"
              class="img-responsive"
              @click="$refs.whitelogoImage.click()"
              style="cursor: pointer"
            />
            <v-card-actions>
              <input
                type="file"
                accept="image/*"
                ref="whitelogoImage"
                style="display: none"
                @change="changeWhiteLogo"
              />
              <v-spacer></v-spacer>
              <v-btn
                class="px-4"
                color="success"
                @click="$refs.whitelogoImage.click()"
                >Change</v-btn
              >
            </v-card-actions>
          </v-card>
        </v-col>
        <v-col cols="12"></v-col>
        <!-- <v-col cols="12" sm="6" md="6" lg=6>
                <v-card class="elevation-5 px-3 py-3">
                    <img :src="darkLogo" alt="Card Image" class="img-responsive" />
                    <v-card-title>
                        <h3 class="headline primary--text">Black Logo</h3>
                    </v-card-title>
                    <v-card-actions>
                        <input type="file" accept="image/*" ref="blacklogoImage" style="display: none"
                            @change="changeBlackLogo">
                        <v-btn class="px-4" color="success" @click="$refs.blacklogoImage.click()">Change</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col> -->
        <v-col cols="12" sm="12" md="12" lg="12">
          <v-card class="elevation-5 px-3 py-3">
            <v-card-title class="justify-space-between">
              <h3 class="headline primary--text">Group Settings</h3>
              <v-btn color="success" @click="showModal = true">
                <v-icon class="zmdi zmdi-plus-circle"></v-icon> &nbsp; New Group
              </v-btn>
            </v-card-title>
            <v-card-text>
              <v-simple-table dense>
                <template v-slot:default>
                  <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th class="text-center">Name</th>
                      <th class="text-center">Avatar</th>
                      <th class="text-center">Provide</th>
                      <th class="text-center">Copy</th>
                      <th class="text-center">See</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(gro, index) in groups" :key="index">
                      <td>{{ index + 1 }}</td>
                      <td>{{ gro.name }}</td>
                      <td>
                        <img
                          :src="gro.avatar"
                          v-if="gro.avatar"
                          alt=""
                          width="50"
                        />
                      </td>
                      <td>{{ gro.canProvide == 1 ? "ON" : "OFF" }}</td>
                      <td>
                        <span
                          class="show-group-chips"
                          v-for="(data, index) in getLabel(gro.canCopy, gro.id)"
                          :key="index"
                          >{{ data }}</span
                        >
                      </td>
                      <td>
                        <span
                          class="show-group-chips"
                          v-for="(data, index) in getLabel(gro.canSee, gro.id)"
                          :key="index"
                          >{{ data }}
                        </span>
                      </td>
                      <td
                        class="align-items-center d-flex justify-space-around"
                      >
                        <v-btn
                          color="success"
                          text
                          icon
                          @click="updateGroupSetting(gro.id, index)"
                        >
                          <v-icon class="zmdi zmdi-edit"></v-icon>
                        </v-btn>
                        <v-btn
                          color="error"
                          text
                          icon
                          @click="deleteGroup(gro.id)"
                        >
                          <v-icon class="zmdi zmdi-delete"></v-icon>
                        </v-btn>
                      </td>
                    </tr>
                  </tbody>
                </template>
              </v-simple-table>
            </v-card-text>
            <!-- <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn
                class="px-4"
                color="success"
                @click="updateGroupSetting"
                :loading="group.loading"
              >
                Update
              </v-btn>
            </v-card-actions> -->
          </v-card>
        </v-col>
      </v-row>
    </v-container>
    <template>
      <v-dialog v-model="showModal" max-width="600">
        <v-card class="pa-6">
          <v-form v-model="form.valid" ref="form" lazy-validation>
            <div class="align-end d-flex justify-space-around mb-5">
              <img
                :src="getGroupAvatar"
                alt="Group Avatar"
                width="300"
                v-if="form.avatar"
                @click="$refs.groupAvatar.click()"
                style="cursor: pointer"
              />
              <span v-else>Choose this group avatar.</span>
              <input
                type="file"
                accept="image/*"
                ref="groupAvatar"
                style="display: none"
                @change="changeGroupAvatar"
              />
              <v-btn
                class="px-4"
                color="success"
                @click="$refs.groupAvatar.click()"
              >
                Change
              </v-btn>
            </div>
            <v-text-field
              label="Group Name"
              v-model="form.name"
              :rules="form.nameRules"
              type="text"
              class="mt-0 pt-0"
            ></v-text-field>
            <v-checkbox
              class="mb-3 mt-0"
              label="Enable Provide"
              color="primary"
              v-model="form.canProvide"
            />
            <v-select
              :items="groupItems(update_id)"
              label="Can Copy"
              v-model="form.canCopy"
              multiple
              chips
              class="pb-2"
            ></v-select>
            <v-select
              :items="groupItems(update_id)"
              label="Can See"
              v-model="form.canSee"
              multiple
              chips
              class="pb-2 pt-0"
            ></v-select>
            <div class="text-right">
              <v-btn
                @click="updateGroup"
                :disabled="!form.valid"
                color="success"
                class="mr-3"
              >
                {{ upsertString }}
              </v-btn>
              <v-btn color="primary" @click="showModal = false" class="px-4">
                Cancel
              </v-btn>
            </div>
          </v-form>
        </v-card>
      </v-dialog>
    </template>
    <confirmation-dialog
      ref="deleteConfirmationDialog"
      heading="Are You Sure You Want To Delete?"
      message="Are you sure you want to Delete this Group?"
      @onConfirm="deleteGroupConfirm"
      confirmColor="red"
    >
    </confirmation-dialog>
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import webServices from "WebServices";
import dateformat from "dateformat";
import axios from "axios";
import Vue from "vue";
import Nprogress from "nprogress";

export default {
  data() {
    return {
      settings_loading: false,
      delete_id: -1,
      update_id: -1,
      showModal: false,
      form: {
        valid: false,
        name: "",
        nameRules: [
          (v) => {
            if (!v) {
              return "Group Name is required";
            } else {
              if (
                this.groupItems(this.update_id).filter((item) => item.text == v)
                  .length > 0
              ) {
                return "Group Name already exist.";
              }
              return true;
            }
          },
        ],
        avatar: undefined,
        canProvide: 0,
        canCopy: [],
        canSee: [],
      },
    };
  },
  mounted() {
    // this.group.loading = true;
    this.getGroups();
  },
  computed: {
    ...mapGetters(["appLogo", "darkLogo", "groups", "getUser"]),
    groupItems() {
      return (id) =>
        this.groups
          .filter((group) => group.id !== id)
          .map((group) => ({
            value: group.id.toString(),
            text: group.name,
          }));
    },
    getLabel() {
      return (ids, id) =>
        this.groupItems(id)
          .filter((item) => ids?.split(",").includes(item.value.toString()))
          .map((item) => item.text);
    },
    getGroupAvatar() {
      if (typeof this.form.avatar === "string") {
        return this.form.avatar;
      } else {
        return URL.createObjectURL(this.form.avatar);
      }
    },
    upsertString() {
      return this.update_id < 0 ? "Create" : "Update";
    },
  },
  methods: {
    ...mapActions([
      "setWhiteLogo",
      "setBlackLogo",
      "getGroups",
      "changeGroupSettingAction",
    ]),
    changeWhiteLogo(event) {
      var files = event.target.files || event.dataTransfer.files;
      if (!files.length) return;
      var formData = new FormData();
      formData.append("whiteLogo", files[0]);
      Nprogress.start();
      axios
        .post(`${webServices.baseURL}/logo/white`, formData, {
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
          const filename = data.response.filename;
          this.setWhiteLogo(filename);
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
    changeBlackLogo(event) {
      var files = event.target.files || event.dataTransfer.files;
      if (!files.length) return;
      var formData = new FormData();
      formData.append("blackLogo", files[0]);
      Nprogress.start();
      axios
        .post(`${webServices.baseURL}/logo/black`, formData, {
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
          const filename = data.response.filename;
          this.setBlackLogo(filename);
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
    updateGroupSetting(id, index) {
      this.update_id = id;
      this.showModal = true;
      this.form.name = this.groups[index].name;
      this.form.avatar = this.groups[index].avatar;
      this.form.canProvide = this.groups[index].canProvide;
      this.form.canCopy = this.groups[index].canCopy?.split(",") || [];
      this.form.canSee = this.groups[index].canSee?.split(",") || [];
    },
    updateGroup() {
      if (this.$refs.form.validate()) {
        let formData = new FormData();
        const { name, avatar, canProvide, canCopy, canSee } = this.form;
        formData.append("name", name);
        formData.append("avatar", avatar);
        formData.append("canProvide", Number(canProvide));
        formData.append("canCopy", canCopy);
        formData.append("canSee", canSee);
        if (this.update_id >= 0) {
          axios
            .post(`${webServices.baseURL}/groups/${this.update_id}`, formData, {
              headers: {
                "Content-Type": "multipart/form-data",
              },
            })
            .then(({ data }) => {
              this.getGroups();
              Vue.notify({
                group: "signals",
                type: "success",
                text: data.response.message,
              });
              if (data.response.group.id === this.getUser.group.id) {
                this.changeGroupSettingAction(data.response.group);
              }
            })
            .catch(({ response }) => {
              Vue.notify({
                group: "signals",
                type: "error",
                text: response.data.response.message,
              });
            })
            .finally(() => {
              this.showModal = false;
              this.update_id = -1;
            });
        } else {
          axios
            .post(`${webServices.baseURL}/groups`, formData, {
              headers: {
                "Content-Type": "multipart/form-data",
              },
            })
            .then(({ data }) => {
              this.getGroups();
              Vue.notify({
                group: "signals",
                type: "success",
                text: data.response.message,
              });
            })
            .catch(({ response }) => {
              Vue.notify({
                group: "signals",
                type: "error",
                text: response.data.response.message,
              });
            })
            .finally(() => {
              this.showModal = false;
            });
        }
      }
    },
    changeGroupAvatar(event) {
      var files = event.target.files || event.dataTransfer.files;
      if (!files.length) return;
      this.form.avatar = files[0];
    },
    deleteGroup(id) {
      this.delete_id = id;
      this.$refs.deleteConfirmationDialog.openDialog();
    },
    deleteGroupConfirm() {
      axios
        .delete(`${webServices.baseURL}/groups/${this.delete_id}`)
        .then(({ data }) => {
          this.getGroups();
          Vue.notify({
            group: "signals",
            type: "success",
            text: "Group deleted!",
          });
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
  },
  watch: {
    "form.canCopy": function (newValue, oldValue) {
      if (!newValue || !oldValue) return;
      let newItem = newValue.filter((item) => !oldValue.includes(item));
      if (newItem.length) {
        if (!this.form.canSee.includes(newItem[0]))
          this.form.canSee.push(newItem[0]);
      }
    },
    "form.canSee": function (newValue, oldValue) {
      if (!newValue || !oldValue) return;
      let oldItem = oldValue.filter((item) => !newValue.includes(item));
      if (oldItem.length) {
        if (this.form.canCopy.includes(oldItem[0]))
          this.form.canCopy = this.form.canCopy.filter(
            (item) => item !== oldItem[0]
          );
      }
    },
    showModal: function (newValue) {
      if (!newValue) {
        this.update_id = -1;
        this.form.name = "";
        this.form.avatar = "";
        this.form.canProvide = 0;
        this.form.canCopy = [];
        this.form.canSee = [];
      }
    },
  },
};
</script>