<template>
  <v-container>
    <v-skeleton-loader
      type="table"
      :loading="isTableLoading"
      transition="fade-transition"
    >
      <v-data-table
        :headers="headers"
        :items="mhs.mahasiswa"
        :items-per-page="10"
        style="background-color: #2e7d323b"
        class="elevation-10 mb-10"
      >
        <template v-slot:item.actions="{ item }">
          <v-icon
            small
            class="mr-2"
            @click="showChangePass(item)"
            title="Ubah Pasword"
          >mdi-textbox-password</v-icon>
          <v-icon
            small
            class="mr-2"
            @click="editItem(item)"
            title="Edit"
          >mdi-pencil</v-icon>
          <v-icon
            small
            @click="deleteItem(item)"
            title="Delete"
          >mdi-delete</v-icon>
        </template>
        <template v-slot:no-data>no data</template>
      </v-data-table>
    </v-skeleton-loader>
    <!-- bottom sheet tambah -->
    <v-bottom-sheet
      scrollable
      width="70%"
      inset
      overlay-color="#69F0AE"
      v-model="toggleMahasiswa"
    >
      <template>
        <v-card>
          <v-card-title>
            <span>Tambah Mahasiswa</span>
            <v-spacer></v-spacer>
            <v-btn
              @click="toggleMahasiswa = false"
              text
            >batal</v-btn>
            <v-btn
              color="#2E7D32"
              :loading="btnLoading"
              @click="save()"
            >Simpan</v-btn>
          </v-card-title>

          <v-tabs
            v-model="tab"
            fixed-tabs
            background-color="black"
            dark
          >
            <v-tab href="#single">
              Tambah Mahasiswa
            </v-tab>
            <v-tab href="#mass">
              Tambah massal Mahasiswa
            </v-tab>
          </v-tabs>
          <v-card-text>
            <vue-scroll :ops="ops">
              <v-tabs-items v-model="tab">
                <v-tab-item :value="'single'">
                  <v-card-text>
                    <v-row
                      dense
                      class="mx-1"
                    >
                      <v-col>
                        <v-text-field
                          color="#C8E6C9"
                          label="Nama"
                          v-model="form.nama"
                        ></v-text-field>
                      </v-col>
                    </v-row>
                    <v-row
                      dense
                      class="mx-1"
                    >
                      <v-col>
                        <v-text-field
                          color="#C8E6C9"
                          label="NIM"
                          v-model="form.nim"
                          type="number"
                          min="0"
                        ></v-text-field>
                      </v-col>
                    </v-row>
                    <v-row
                      dense
                      class="mx-1"
                    >
                      <v-col>
                        <v-text-field
                          color="#C8E6C9"
                          label="Password"
                          v-model="form.password"
                          type="password"
                        ></v-text-field>
                      </v-col>
                    </v-row>
                    <v-row
                      dense
                      class="mx-1"
                    >
                      <v-col>
                        <v-select
                          label="Jurusan"
                          color="white"
                          :items="jurusan"
                          item-text="nama"
                          item-value="id"
                          v-model="form.jurusan_id"
                        ></v-select>
                      </v-col>
                    </v-row>
                    <v-row
                      dense
                      class="mx-1"
                    >
                      <v-col>
                        <v-text-field
                          color="#C8E6C9"
                          label="Email"
                          v-model="form.email"
                          type="email"
                        ></v-text-field>
                      </v-col>
                    </v-row>
                    <v-row
                      dense
                      class="mx-1"
                    >
                      <v-col>
                        <v-text-field
                          color="#C8E6C9"
                          label="Tempat Lahir"
                          v-model="form.tmpt_lahir"
                        ></v-text-field>
                      </v-col>
                      <v-col cols="6">
                        <v-menu
                          v-model="tglLahir"
                          :close-on-content-click="false"
                          :nudge-right="40"
                          transition="scale-transition"
                          offset-y
                          min-width="290px"
                        >
                          <template v-slot:activator="{ on, attrs }">
                            <v-text-field
                              v-model="form.tgl_lahir"
                              label="Tanggal Lahir"
                              prepend-icon="event"
                              readonly
                              v-bind="attrs"
                              v-on="on"
                            ></v-text-field>
                          </template>
                          <v-date-picker
                            v-model="form.tgl_lahir"
                            locale="id-ID"
                          >
                          </v-date-picker>
                        </v-menu>
                      </v-col>
                    </v-row>
                    <v-row
                      dense
                      class="mx-1"
                    >
                      <v-col>
                        <v-text-field
                          color="#C8E6C9"
                          label="No. Handphone"
                          v-model="form.hp"
                        ></v-text-field>
                      </v-col>
                    </v-row>
                    <v-row
                      dense
                      class="mx-1"
                    >
                      <v-col>
                        <v-text-field
                          color="#C8E6C9"
                          label="Semester"
                          v-model="form.semester"
                          type="number"
                          min="0"
                          max="14"
                        ></v-text-field>
                      </v-col>
                      <v-col>
                        <v-text-field
                          color="#C8E6C9"
                          label="IPK"
                          v-model="form.ipk"
                          type="number"
                          min="0"
                          max="4"
                        ></v-text-field>
                      </v-col>
                    </v-row>
                  </v-card-text>
                </v-tab-item>
                <v-tab-item :value="'mass'">
                  <v-card-text class="mx-3 my-2">
                    <v-timeline
                      align-top
                      dense
                    >
                      <v-timeline-item
                        color="blue"
                        small
                      >
                        <div>
                          <div class="font-weight-normal">
                            <strong>Download file .xlx</strong>
                          </div>
                          <div>
                            <p>
                              Download file template excel
                            </p>
                            <v-btn
                              color="#2E7D32"
                              :loading="btnLoading"
                              @click="downloadTemplate()"
                            ><i class="mdi mdi-download mr-2"></i> Download template excel kosong</v-btn>
                          </div>
                        </div>
                      </v-timeline-item>
                      <v-timeline-item
                        color="blue"
                        small
                      >
                        <div>
                          <div class="font-weight-normal">
                            <strong>Tambahkan info mahasiswa dalam template excel.</strong>
                          </div>
                          <div class="pr-5">
                            <p>
                              Kolom yang wajib diisi adalah nama, nim, password, dan email.
                            </p>
                            <v-simple-table
                              light
                              dense
                            >
                              <thead>
                                <tr>
                                  <th>A</th>
                                  <th>B</th>
                                  <th>C</th>
                                  <th>D</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td><strong>Nama</strong></td>
                                  <td><strong>NIM</strong></td>
                                  <td><strong>Password</strong></td>
                                  <td><strong>Email</strong></td>
                                </tr>
                                <tr>
                                  <td>Jhon</td>
                                  <td>11750115076</td>
                                  <td>Password123</td>
                                  <td>11750115076@students.uin-suska.ac.id</td>
                                </tr>
                              </tbody>
                            </v-simple-table>
                          </div>
                        </div>
                      </v-timeline-item>
                      <v-timeline-item
                        color="blue"
                        small
                      >
                        <div>
                          <div class="font-weight-normal">
                            <strong>Upload file .xlx</strong>
                          </div>
                          <div>
                            <div v-if="file">
                              <p class="text-muted">File terlampir</p>
                              <p>{{file.name}} <i
                                  class="mdi mdi-close"
                                  @click="file=''"
                                ></i></p>
                            </div>
                            <v-btn
                              color="#2E7D32"
                              :loading="btnLoading"
                              @click="uploadTemplate()"
                            ><i class="mdi mdi-attachment mr-2"></i> Lampirkan file excel</v-btn>
                            <v-file-input
                              id="upload"
                              v-model="file"
                              hide-input
                              truncate-length="1"
                              class="d-none"
                            ></v-file-input>
                          </div>
                        </div>
                      </v-timeline-item>
                    </v-timeline>
                  </v-card-text>
                </v-tab-item>
              </v-tabs-items>
            </vue-scroll>
          </v-card-text>
        </v-card>
      </template>
    </v-bottom-sheet>
    <!-- bottom sheet edit -->
    <v-bottom-sheet
      scrollable
      width="60%"
      inset
      overlay-color="#69F0AE"
      v-model="toggleMahasiswaEdit"
    >
      <v-card>
        <v-card-title>
          <span>Edit Mahasiswa</span>
          <v-spacer></v-spacer>
          <v-btn
            @click="toggleMahasiswaEdit = false"
            text
          >batal</v-btn>
          <v-btn
            color="#2E7D32"
            :loading="btnLoading"
            @click="saveEdit()"
          >Simpan</v-btn>
        </v-card-title>
        <v-card-text style="height: 600px;">
          <v-row
            dense
            class="mx-1"
          >
            <v-col>
              <v-text-field
                color="#C8E6C9"
                label="Nama"
                v-model="form.nama"
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row
            dense
            class="mx-1"
          >
            <v-col>
              <v-text-field
                color="#C8E6C9"
                label="NIM"
                v-model="form.nim"
                type="number"
                min="0"
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row
            dense
            class="mx-1"
          >
            <v-col>
              <v-text-field
                color="#C8E6C9"
                label="Email"
                v-model="form.email"
                type="email"
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row
            dense
            class="mx-1"
          >
            <v-col>
              <v-text-field
                color="#C8E6C9"
                label="Tempat Lahir"
                v-model="form.tmpt_lahir"
              ></v-text-field>
            </v-col>
            <v-col cols="6">
              <v-menu
                v-model="tglLahir"
                :close-on-content-click="false"
                :nudge-right="40"
                transition="scale-transition"
                offset-y
                min-width="290px"
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    v-model="form.tgl_lahir"
                    label="Tanggal Lahir"
                    prepend-icon="event"
                    readonly
                    v-bind="attrs"
                    v-on="on"
                  ></v-text-field>
                </template>
                <v-date-picker
                  v-model="form.tgl_lahir"
                  locale="id-ID"
                >
                </v-date-picker>
              </v-menu>
            </v-col>
          </v-row>
          <v-row
            dense
            class="mx-1"
          >
            <v-col>
              <v-text-field
                color="#C8E6C9"
                label="No. Handphone"
                v-model="form.hp"
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row
            dense
            class="mx-1"
          >
            <v-col>
              <v-text-field
                color="#C8E6C9"
                label="Semester"
                v-model="form.semester"
                type="number"
                min="0"
                max="14"
              ></v-text-field>
            </v-col>
            <v-col>
              <v-text-field
                color="#C8E6C9"
                label="IPK"
                v-model="form.ipk"
                type="number"
                min="0"
                max="4"
              ></v-text-field>
            </v-col>
          </v-row>
        </v-card-text>
      </v-card>
    </v-bottom-sheet>
    <!-- Dialog Delete -->
    <div class="text-center">
      <v-dialog
        v-model="dialogDelete"
        width="400"
      >
        <v-card>
          <v-card-title
            class="headline white--text"
            primary-title
          >
            <v-icon
              color="white"
              class="mr-2"
            >delete</v-icon>Hapus Mahasiswa
          </v-card-title>

          <v-card-text class="mt-2 white--text">
            Apakah anda yakin akan menghapus Mahasiswa
            <span class="font-weight-bold"></span>?
          </v-card-text>

          <v-divider></v-divider>

          <v-card-actions>
            <v-btn
              @click="dialogDelete = false"
              color="white"
              text
            >batal</v-btn>
            <v-spacer></v-spacer>
            <v-btn
              color="red"
              dark
              @click="dialogDelete = false, deleteConfirmed()"
            >
              <v-icon>delete</v-icon>Hapus
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </div>
    <!-- change pass modal -->
    <v-bottom-sheet
      width="60%"
      inset
      overlay-color="#69F0AE"
      v-model="toggleChangePass"
    >
      <v-card>
        <v-card-title>
          <span>Ubah Password</span>
          <v-spacer></v-spacer>
          <v-btn
            @click="toggleChangePass = false"
            text
          >batal</v-btn>
          <v-btn
            color="#2E7D32"
            :loading="btnLoading"
            @click="changePass()"
          >Simpan</v-btn>
        </v-card-title>
        <v-card-text style="height: 100px;">
          <v-row
            dense
            class="mx-1"
          >
            <v-col>
              <v-text-field
                color="#C8E6C9"
                label="Password Baru"
                v-model="form.password"
                type="password"
              ></v-text-field>
            </v-col>
            <v-col>
              <v-text-field
                color="#C8E6C9"
                label="Konfirmasi Password Baru"
                v-model="form.password2"
                type="password"
              ></v-text-field>
            </v-col>
          </v-row>
        </v-card-text>
      </v-card>
    </v-bottom-sheet>
    <!-- Snackbar -->
    <v-snackbar
      v-model="snackbar.show"
      :timeout="2000"
    >
      {{ snackbar.message }}

      <template v-slot:action="{ attrs }">
        <v-btn
          :color="snackbar.color"
          text
          v-bind="attrs"
          @click="snackbar.show = false"
        >
          Close
        </v-btn>
      </template>
    </v-snackbar>
    <!-- Akhir -->
  </v-container>
</template>

<script>
import { mapActions, mapMutations, mapState } from "vuex";
export default {
  created() {
    this.getJurusan();
    this.getMahasiswa();
  },
  methods: {
    ...mapMutations(["toggleOpenBeasiswa"]),
    ...mapActions([
      "getJurusan",
      "getMahasiswa",
      "storeMahasiswa",
      "editMahasiswa",
      "deleteMahasiswa",
      "editPassword",
      "importMahasiswa"
    ]),
    save() {
      if (this.tab == "single") {
        var data = this.form;
        console.log(data);
        this.btnLoading = true;
        this.storeMahasiswa(data)
          .then(response => {
            this.btnLoading = false;
            this.toggleMahasiswa = false;
            this.form = {};
          })
          .catch(error => {
            this.btnLoading = false;
          });
      } else if (this.tab == "mass") {
        var formData = new FormData();
        var file = this.file;
        formData.append("file", file);
        this.btnLoading = true;
        this.importMahasiswa(formData)
          .then(response => {
            this.btnLoading = false;
            this.toggleMahasiswa = false;
            this.form = {};
            this.file = "";
            this.snackbar.show = true;
            this.snackbar.color = "blue";
            this.snackbar.message = "Mahasiswa berhasil ditambahkan!";
          })
          .catch(error => {
            this.btnLoading = false;
          });
      }
    },
    deleteItem(item) {
      this.dialogDelete = true;
      this.id = item.nim;
    },
    deleteConfirmed() {
      this.btnLoading = true;
      this.deleteMahasiswa(this.id)
        .then(response => {
          this.btnLoading = false;
          this.toggleMahasiswa = false;
        })
        .catch(error => {
          this.btnLoading = false;
        });
    },
    editItem(item) {
      this.toggleMahasiswaEdit = true;
      this.form = item;
    },
    saveEdit() {
      var data = this.form;
      this.btnLoading = true;
      this.editMahasiswa(data)
        .then(response => {
          this.btnLoading = false;
          this.toggleMahasiswaEdit = false;
          this.form = {};
        })
        .catch(error => {
          this.btnLoading = false;
        });
    },
    showChangePass(item) {
      this.toggleChangePass = true;
      this.form = item;
      this.id = item.id;
    },
    changePass() {
      var data = this.form;
      if (data.password == data.password2) {
        this.btnLoading = true;
        this.editMahasiswa(data)
          .then(response => {
            this.btnLoading = false;
            this.toggleChangePass = false;
            this.form = {};
            this.snackbar.show = true;
            this.snackbar.color = "blue";
            this.snackbar.message = "Ubah password berhasil!";
          })
          .catch(error => {
            this.btnLoading = false;
          });
      } else {
        this.snackbar.show = true;
        this.snackbar.color = "red";
        this.snackbar.message = "Konfirmasi password harus sama!";
      }
    },
    uploadTemplate() {
      document.getElementById("upload").click();
    },
    downloadTemplate() {
      location = this.url + "/api/user/export";
    }
  },
  computed: {
    ...mapState([
      "jurusan",
      "url",
      "isOpenBeasiswa",
      "mhs",
      "isTableLoading",
      "isLoading"
    ]),
    toggleMahasiswa: {
      get: function() {
        return this.isOpenBeasiswa;
      },
      set: function(data) {
        this.toggleOpenBeasiswa(data);
      }
    }
  },
  data() {
    return {
      ops: {
        scrollPanel: {
          easing: "easeInQuad",
          speed: 800,
          scrollingX: false
        },
        vuescroll: {
          wheelScrollDuration: 0,
          wheelDirectionReverse: true
        }
      },
      btnLoading: false,
      id: "",
      file: "",
      form: {},
      tab: null,
      tglLahir: false,
      toggleMahasiswaEdit: false,
      toggleChangePass: false,
      dialogDelete: false,
      snackbar: {
        show: false,
        message: ""
      },
      headers: [
        {
          text: "Nama Mahasiswa",
          align: "start",
          sortable: false,
          value: "nama"
        },
        {
          text: "NIM",
          align: "center",
          sortable: false,
          value: "nim"
        },
        {
          text: "Jurusan",
          align: "center",
          sortable: false,
          value: "jurusan.nama"
        },
        { text: "Actions", value: "actions", sortable: false }
      ]
    };
  }
};
</script>

<style>
</style>
