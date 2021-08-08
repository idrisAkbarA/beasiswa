<template>
  <v-container>
    <v-card>
      <v-card-title>
        List Beasiswa Ongoing/Selesai Beserta Permohonannya
      </v-card-title>
      <v-card-subtitle>
        Lihat list beasiswa ongoing/selesai serta kelola permohonan beasiswa tersebut.
        <br>
        Beasiswa yang masih dibuka dapat di set/edit ulang pada halaman
        <v-chip
          color="green"
          small
          label
          outlined
        >
          <router-link
            class="text-white"
            to="beasiswa"
          > Beasiswa</router-link>.

        </v-chip>

      </v-card-subtitle>
      <v-card-subtitle>

      </v-card-subtitle>
    </v-card>
    <v-skeleton-loader
      type="table"
      :loading="isTableLoading"
      transition="fade-transition"
    >
      <v-data-table
        :headers="headers.beasiswa"
        :items="beasiswaProgress.selesai"
        :items-per-page="10"
        @click:row="info"
        style="background-color: #2e7d323b; cursor: hand"
        class="elevation-10 mb-10 row-pointer"
      >
        <template v-slot:item.status="{ item }">
          <v-chip :color="item.status == 'Selesai' ? 'green' : 'orange'">{{
            item.status
          }}</v-chip>
        </template>
        <template v-slot:no-data>no data</template>
      </v-data-table>
    </v-skeleton-loader>

    <v-dialog
      v-model="dialog"
      fullscreen
      hide-overlay
      transition="dialog-bottom-transition"
    >
      <v-card>
        <v-toolbar
          dark
          color="green"
        >
          <v-btn
            icon
            dark
            @click="dialog = false"
          >
            <v-icon>mdi-close</v-icon>
          </v-btn>
          <v-toolbar-title>{{ selectedBeasiswa.nama }}</v-toolbar-title>
          <v-spacer></v-spacer>
        </v-toolbar>
        <v-card-text class="mt-5">
          <v-tabs fixed-tabs>
            <v-tab>Laporan</v-tab>
            <v-tab>Info</v-tab>
            <v-tab-item>
              <v-col cols="12">
                <v-row>
                  <div class="col-lg-10 col-md-12 mb-5">
                    <v-chip
                      color="blue"
                      :outlined="filter != 'permohonan'"
                      class="mr-2 mb-2"
                      @click="filter = 'permohonan'"
                    >Semua ({{
                        undefined !== selectedBeasiswa.permohonan
                          ? selectedBeasiswa.permohonan.length
                          : 0
                      }})</v-chip>
                    <v-chip
                      color="orange"
                      :outlined="filter != 'tidak_lengkap'"
                      class="mr-2 mb-2"
                      @click="filter = 'tidak_lengkap'"
                    >Tidak Lengkap ({{
                        undefined !== selectedBeasiswa.tidak_lengkap
                          ? selectedBeasiswa.tidak_lengkap.length
                          : 0
                      }})</v-chip>
                    <v-chip
                      color="cyan"
                      :outlined="filter != 'on_progress'"
                      class="mr-2 mb-2"
                      @click="filter = 'on_progress'"
                    >Proses ({{
                        undefined !== selectedBeasiswa.on_progress
                          ? selectedBeasiswa.on_progress.length
                          : 0
                      }})</v-chip>
                    <v-chip
                      color="red"
                      :outlined="filter != 'tidak_lulus'"
                      class="mr-2 mb-2"
                      @click="filter = 'tidak_lulus'"
                    >Tidak Lulus ({{
                        undefined !== selectedBeasiswa.tidak_lulus
                          ? selectedBeasiswa.tidak_lulus.length
                          : 0
                      }})</v-chip>
                    <v-chip
                      color="green"
                      :outlined="filter != 'lulus'"
                      class="mr-2 mb-2"
                      @click="filter = 'lulus'"
                    >Lulus ({{
                        undefined !== selectedBeasiswa.lulus
                          ? selectedBeasiswa.lulus.length
                          : 0
                      }})</v-chip>
                  </div>
                  <div class="col-lg-2 col-md-12 mb-5">
                    <v-spacer></v-spacer>
                    <v-chip
                      light
                      class="float-right"
                      v-if="
                        selectedBeasiswa.deleted_at
                      "
                      @click.stop="setKelulusanEditor()"
                    >
                      <v-icon class="mr-2">mdi-checkbox-marked-circle-outline</v-icon>Edit Kelulusan
                    </v-chip>
                  </div>
                </v-row>
                <v-card-title>
                  <v-text-field
                    v-model="search.permohonan"
                    append-icon="mdi-magnify"
                    label="Search"
                    single-line
                    hide-details
                  ></v-text-field>
                </v-card-title>
                <v-data-table
                  :headers="headers.pemohon"
                  :items="selectedBeasiswa[filter]"
                  :items-per-page="10"
                  :search="search.permohonan"
                  :loading="isLoading"
                  @click:row="getUserPermohonan"
                  style="background-color: #2e7d323b"
                  class="elevation-10 mb-10 row-pointer"
                >
                  <template v-slot:item.verificator="{ item }">
                    <v-chip
                      dark
                      v-if="
                        item.is_berkas_passed != null || item.is_submitted == 0
                      "
                      :color="item.is_berkas_passed ? 'green' : 'red'"
                    >
                      <i :class="`mdi ${
                          item.is_berkas_passed ? 'mdi-check' : 'mdi-close'
                        } mr-2`"></i>
                      {{ item.verificator }}
                    </v-chip>
                    <p
                      v-else
                      class="text-caption"
                    >-</p>
                  </template>
                  <template v-slot:item.interviewer="{ item }">
                    <v-chip
                      dark
                      v-if="item.is_interview_passed != null"
                      :color="item.is_interview_passed ? 'green' : 'red'"
                    >
                      <i :class="`mdi ${
                          item.is_interview_passed ? 'mdi-check' : 'mdi-close'
                        } mr-2`"></i>
                      {{ item.interviewer }}
                    </v-chip>
                    <p
                      v-else
                      class="text-caption"
                    >-</p>
                  </template>
                  <template v-slot:item.surveyor="{ item }">
                    <v-chip
                      dark
                      v-if="item.is_survey_passed != null"
                      :color="item.is_survey_passed ? 'green' : 'red'"
                    >
                      <i :class="`mdi ${
                          item.is_survey_passed ? 'mdi-check' : 'mdi-close'
                        } mr-2`"></i>
                      {{ item.surveyor }}
                    </v-chip>
                    <p
                      v-else
                      class="text-caption"
                    >-</p>
                  </template>
                  <template v-slot:item.selector="{ item }">
                    <v-chip
                      dark
                      v-if="item.is_selection_passed != null"
                      :color="item.is_selection_passed ? 'green' : 'red'"
                    >
                      <i :class="`mdi ${
                          item.is_selection_passed ? 'mdi-check' : 'mdi-close'
                        } mr-2`"></i>
                      {{ item.selector }}
                    </v-chip>
                    <p
                      v-else
                      class="text-caption"
                    >-</p>
                  </template>
                  <template v-slot:no-data>no data</template>
                </v-data-table>
              </v-col>
            </v-tab-item>
            <v-tab-item>
              <v-col cols="12">
                <v-data-table
                  :headers="headers.detailBeasiswa"
                  :items="detailBeasiswa"
                  hide-default-header
                  hide-default-footer
                  class="elevation-1"
                ></v-data-table>
                <p class="mt-3">{{ selectedBeasiswa.deskripsi }}</p>
              </v-col>
            </v-tab-item>
          </v-tabs>
        </v-card-text>
      </v-card>

      <!-- right sheet -->
      <v-dialog
        overlay-color="green"
        v-if="drawer"
        v-model="drawer"
        width="600px"
        scrollable
      >
        <v-card>
          <v-card-title>Edit Kelulusan {{selectedBeasiswa.nama}}</v-card-title>
          <v-card-subtitle>
            Edit kelulusan beasiswa seperti menambah dan menghapus mahasiswa

          </v-card-subtitle>
          <v-card-text style="height: 800px;">
            <!-- <v-row>
              <v-col>
                <p><strong>Set Kuota</strong><br>Kurangi atau tambah kuota penerima beasiswa</p>
                <v-form>
                  <v-text-field
                    required
                    :rules="ruleKuota"
                    v-model="kuotaTemp"
                    dense
                    color="green"
                    label="Kuota"
                    persistent-hint
                    outlined
                    type="number"
                    hide-details="auto"
                  >
                    <template v-slot:append-outer>
                      <v-btn
                        style="margin: 0px !important"
                        color="green"
                        :disabled="kuotaTemp<selectedBeasiswa[filter].length"
                      >Set Kuota</v-btn>
                    </template>
                  </v-text-field>
                </v-form>
                <p>Note: Anda tidak dapat menambah mahasiswa jika kuota telah penuh</p>
              </v-col>
            </v-row> -->
            <v-row>
              <v-col cols="12">
                <p><strong>Kuota Beasiswa</strong><br>
                  Dengan menambah atau mengurangi mahasiswa maka jumlah kuota akan ikut menyesuaikan</p>
                <p><strong>Set Mahasiswa</strong><br>
                  Tambah atau hapus mahasiswa dari list kelulusan di bawah ini atau
                  Anda dapat menambah mahasiswa dengan
                  <v-chip
                    small
                    color="green"
                    outlined
                    @click="drawer=false; drawerExcel = true;"
                  >Import Excel</v-chip>
                </p>
                <v-text-field
                  v-model="searchEdit"
                  label="Cari Mahasiswa"
                  dense
                  hide-details
                  color="green"
                  outlined
                  prepend-inner-icon="mdi-magnify"
                  clearable
                ></v-text-field>

              </v-col>
            </v-row>
            <v-row>
              <v-data-table
                style="width:100%"
                :headers="headersEdit"
                :items="listMHSTemp"
                :search="searchEdit"
                :items-per-page="-1"
                hide-default-footer
              >
                <template v-slot:item.actions="{ item }">
                  <v-btn
                    small
                    color="red"
                    left
                    @click="deleteMHS(index,item)"
                  >
                    <v-icon>mdi-delete</v-icon> Hapus Mahasiswa
                  </v-btn>
                </template>
              </v-data-table>

              <!-- <v-list-item
                v-for="(item,index) in listMHSTemp"
                :key="index"
              >
                <v-list-item-content style="padding:0px !important">
                  <v-row align="center">
                    <v-col cols="3">{{item.mhs_id}}</v-col>
                    <v-col cols="4">{{item.mahasiswa?item.mahasiswa.nama:'-'}}</v-col>
                    <v-col cols="5">
                      <v-spacer></v-spacer>
                      <v-btn
                        small
                        color="red"
                        left
                        @click="deleteMHS(index,item)"
                      >
                        <v-icon>mdi-delete</v-icon> Hapus Mahasiswa
                      </v-btn>
                    </v-col>
                  </v-row>
                </v-list-item-content>
              </v-list-item> -->

            </v-row>
          </v-card-text>
          <v-card-actions class="overflow-x-hidden">
            <v-row>
              <v-list-item>
                <v-list-item-content style="padding:0px !important">
                  <v-row align="center">
                    <v-col cols="7">
                      <v-text-field
                        outlined
                        color="green"
                        label="NIM"
                        dense
                        hide-details=""
                        v-model="insertNIM"
                      >
                      </v-text-field>
                    </v-col>

                    <v-col cols="5">
                      <v-spacer></v-spacer>
                      <v-btn
                        :disabled="insertNIM==null||insertNIM==''"
                        @click="insertMHS()"
                        small
                        color="green"
                        left
                      >
                        <v-icon>mdi-plus</v-icon> Tambah Mahasiswa
                      </v-btn>
                    </v-col>
                  </v-row>
                </v-list-item-content>
              </v-list-item>
              <v-divider></v-divider>
              <div class="d-flex ml-4 mt-2">
                <v-btn color="green">Simpan Perubahan</v-btn>
                <v-btn text>batal</v-btn>
              </div>

            </v-row>
          </v-card-actions>
        </v-card>
      </v-dialog>
      <v-navigation-drawer
        v-if="drawerExcel"
        v-model="drawerExcel"
        absolute
        temporary
        right
        height="100%"
        width="50vw"
      >
        <v-list-item>
          <v-list-item-content>
            <v-list-item-title>
              <strong>{{ selectedBeasiswa.nama }}</strong>
            </v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-divider></v-divider>
        <div class="col-12">
          <v-row>
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
                    <p>Download file template excel</p>
                    <v-btn
                      color="#2E7D32"
                      :disabled="btnLoading"
                      @click="downloadTemplate"
                    >
                      <i class="mdi mdi-download mr-2"></i> Download template
                      excel kosong
                    </v-btn>
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
                    <p>Kolom yang wajib diisi adalah nim</p>
                    <v-simple-table
                      light
                      dense
                    >
                      <thead>
                        <tr>
                          <th>A</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>
                            <strong>NIM</strong>
                          </td>
                        </tr>
                        <tr>
                          <td>11750115076</td>
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
                  <div class="font-weight-normal mb-3">
                    <strong>Upload file .xlx</strong>
                  </div>
                  <div>
                    <div v-if="file">
                      <v-chip
                        close
                        small
                        @click:close="file = ''"
                        class="my-2"
                      >{{ file.name }}</v-chip>
                    </div>
                    <v-btn
                      color="#2E7D32"
                      :disabled="btnLoading"
                      @click="$refs.fileInput.$refs.input.click()"
                    >
                      <i class="mdi mdi-attachment mr-2"></i> Lampirkan file
                      excel
                    </v-btn>
                    <v-file-input
                      hide-input
                      ref="fileInput"
                      v-model="file"
                      class="d-none"
                    ></v-file-input>
                  </div>
                </div>
              </v-timeline-item>
            </v-timeline>
          </v-row>
        </div>
        <template v-slot:append>
          <div class="px-2 py-2">
            <v-btn
              color="#2E7D32"
              class="float-right"
              :loading="btnLoading"
              @click="importPermohonan"
            >Save</v-btn>
          </div>
        </template>
      </v-navigation-drawer>
    </v-dialog>
    <v-dialog
      scrollable
      width="500"
      overlay-color="green"
      v-model="dialogMHS"
    >
      <v-card v-if="permohonans">
        <v-card-title>Detail Permohonan</v-card-title>
        <v-card-text>
          <v-col
            cols="12"
            v-if="permohonans.mahasiswa"
          >
            <h6 class="text-light">
              {{ permohonans.mahasiswa.nama }}
            </h6>
            <p class="text-muted text-caption">
              {{ permohonans.mahasiswa.jurusan.nama }} ({{
                permohonans.mahasiswa.fakultas.nama
              }})
            </p>
          </v-col>
          <v-col
            cols="12"
            v-if="permohonans"
          >
            <v-timeline
              dense
              v-if="isShowTimeline(permohonans)"
            >
              <v-slide-x-reverse-transition
                group
                hide-on-leave
              >
                <v-timeline-item
                  v-for="(time, index) in permohonans.timeline"
                  :key="index"
                  small
                  fill-dot
                  :icon="time.icon.icon"
                  :color="time.icon.color"
                >
                  <v-row>
                    <v-col
                      cols="12"
                      xl="8"
                    >
                      <v-alert :class="`${time.color} mb-0 pb-0`">
                        {{ time.msg }}
                        <p class="caption">
                          {{ time.time ? parseDate(time.time) : "" }}
                        </p>
                      </v-alert>
                    </v-col>
                  </v-row>
                </v-timeline-item>
              </v-slide-x-reverse-transition>
            </v-timeline>
          </v-col>
          <v-col
            cols="12"
            v-if="permohonans.keterangan"
          >
            <span>Keterangan :</span>
            <br />
            <p>{{ permohonans.keterangan }}</p>
          </v-col>
        </v-card-text>
      </v-card>
    </v-dialog>
    <!-- Akhir -->
    <!-- bottom sheet create -->
    <v-bottom-sheet
      scrollable
      width="60%"
      inset
      overlay-color="#69F0AE"
      v-model="toggleBeasiswa"
    >
      <v-card>
        <v-card-title>
          <span>Tambah Beasiswa Selesai</span>
          <v-spacer></v-spacer>
          <v-btn
            text
            class="mr-2"
            @click="toggleBeasiswa = false"
          >Batal</v-btn>
          <v-btn
            color="#2E7D32"
            :loading="btnLoading"
            @click="store"
          >Simpan</v-btn>
        </v-card-title>
        <v-card-text style="height: 600px">
          <v-row
            dense
            class="ml-1 mr-1"
          >
            <v-col cols="12">
              <v-text-field
                color="#C8E6C9"
                label="Nama Beasiswa"
                v-model="form.nama"
              ></v-text-field>
            </v-col>
            <v-col cols="12">
              <v-text-field
                color="#C8E6C9"
                label="Deskripsi Beasiswa"
                v-model="form.deskripsi"
              ></v-text-field>
            </v-col>
            <v-col cols="10">
              <v-combobox
                color="white"
                label="Instansi"
                :items="instansi"
                item-text="name"
                item-value="id"
                v-model="form.instansi_id"
              ></v-combobox>
            </v-col>
            <v-col cols="2">
              <v-text-field
                type="number"
                label="Kuota"
                min="0"
                v-model="form.quota"
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
        >Close</v-btn>
      </template>
    </v-snackbar>
  </v-container>
</template>

<script>
import { mapActions, mapMutations, mapState } from "vuex";
export default {
  created() {
    this.getBeasiswaProgress();
    this.getInstansi();
  },
  methods: {
    ...mapMutations(["toggleOpenBeasiswa", "mutateLoading"]),
    ...mapActions(["getBeasiswaProgress", "getInstansi", "storeBeasiswa"]),
    compareType(a, b) {
      a == b ? true : false;
    },
    getPermohonan(id) {
      this.mutateLoading(true);
      axios
        .get(`/api/beasiswa/${id}/permohonan`, {
          params: {
            // tahap: "lulus"
          },
        })
        .then((response) => {
          const item = response.data;
          this.selectedBeasiswa = item;
          this.lulus = item.lulus;
          this.selectedBeasiswa.tidak_lengkap = [];
          this.selectedBeasiswa.tidak_lulus = [];
          this.selectedBeasiswa.on_progress = [];
          item.permohonan.forEach((x) => {
            if (!x.is_submitted) {
              this.selectedBeasiswa.tidak_lengkap.push(x);
            } else if (
              (x.is_selection_passed == null && item.deleted_at != null) ||
              x.is_berkas_passed == 0 ||
              x.is_interview_passed == 0 ||
              x.is_survey_passed == 0 ||
              x.is_selection_passed == 0
            ) {
              this.selectedBeasiswa.tidak_lulus.push(x);
            } else if (x.is_selection_passed == null) {
              this.selectedBeasiswa.on_progress.push(x);
            }
          });
        })
        .catch((error) => {
          if (error.response.status == 401) {
            this.$router.push({ name: "Login Petugas" });
          }
          console.error(error);
          this.snackbar = {
            show: true,
            color: "red",
            message: error,
          };
        })
        .then(() => {
          this.mutateLoading(false);
        });
    },
    store() {
      let form = _.clone(this.form);
      form.selesai = true;
      if (form.instansi_id && typeof form.instansi_id == "object") {
        form.instansi_id = form.instansi_id.id;
      }
      this.storeBeasiswa(form)
        .then(() => {
          this.toggleBeasiswa = false;
          this.getBeasiswaProgress();
        })
        .catch((error) => {
          if (error.response.status == 401) {
            this.$router.push({ name: "Login Petugas" });
          }
          console.error(error);
          this.snackbar = {
            show: true,
            color: "red",
            message: error,
          };
        });
    },
    importPermohonan() {
      var formData = new FormData();
      var file = this.file;
      formData.append("file", file);
      this.btnLoading = true;
      axios
        .post("/api/permohonan/import/" + this.selectedBeasiswa.id, formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then((response) => {
          if (response.data.status) {
            this.drawer = false;
            this.selectedBeasiswa = response.data.data;
            this.form = {};
            this.file = "";
            this.snackbar = {
              show: true,
              color: "blue",
              message: "Success!",
            };
          } else {
            this.snackbar = {
              show: true,
              color: "red",
              message: response.data.message,
            };
          }
        })
        .catch((error) => {
          console.error(error);
          this.snackbar = {
            show: true,
            color: "red",
            message: error,
          };
        })
        .then(() => {
          this.btnLoading = false;
        });
    },
    info(item) {
      this.selectedBeasiswa = "";
      this.dialog = true;
      this.getPermohonan(item.id);
    },
    parseDate: function (date) {
      return this.$moment(date, "YYYY-MM-DD").format("Do MMMM YYYY");
    },
    isShowTimeline(item) {
      return true;
    },
    checkStatus(item) {
      if (item.is_selection_passed == 0) {
        return "Maaf permohonan anda didiskualifikasi.";
      }
      if (item.is_selection_passed == 1) {
        return "Selamat permohonan anda lulus.";
      }
      if (item.is_berkas_passed == 0) {
        return "Maaf permohonan anda didiskualifikasi.";
      }
      if (item.is_interview_passed == 0) {
        return "Maaf permohonan anda didiskualifikasi.";
      }
      if (item.is_survey_passed == 0) {
        return "Maaf permohonan anda didiskualifikasi.";
      }
      if (item.is_survey_passed == 0) {
        return "Maaf permohonan anda didiskualifikasi.";
      }

      if (item.is_survey_passed == 1) {
        return "Menunggu seleksi pimpinan.";
      }
      if (item.is_interview_passed == 1) {
        return "Team surveyor segera melakukan survey ke lokasi anda, harap menunggu.";
      }
      if (item.is_berkas_passed == 1) {
        return "Segera lakukan wawancara pada tanggal yang ditentukan.";
      }
      return "Permohonan anda sedang di proses";
    },
    checkColor(item) {
      if (item.is_berkas_passed == 0) {
        return "ditolak";
      }
      if (item.is_interview_passed == 0) {
        return "ditolak";
      }
      if (item.is_survey_passed == 0) {
        return "ditolak";
      }
      if (item.is_survey_passed == 0) {
        return "ditolak";
      }
      if (item.is_selection_passed == 0) {
        return "ditolak";
      }
      if (item.is_selection_passed == 1) {
        return "diterima";
      }
      return "#2e7d323b";
    },
    parseDate(date) {
      return this.$moment(date, "YYYY-MM-DD").format("Do MMMM YYYY");
    },
    getUserPermohonan(item) {
      this.dialogMHS = true;
      this.permohonans = item;
      this.permohonans["beasiswa"] = this.selectedBeasiswa;
      this.addTimeline();
    },
    addTimeline() {
      const permohonan = this.permohonans;
      const beasiswa = this.selectedBeasiswa;
      let status = true;
      var timeline = [];

      status = permohonan.is_submitted || beasiswa.tahap == "Tahap Berkas";
      timeline.push({
        kegiatan: "Kelengkapan Berkas",
        msg: "Melengkapi berkas",
        is_done: status,
        time: permohonan.is_submitted ? permohonan.created_at : "",
        color:
          beasiswa.tahap != "Tahap Berkas"
            ? permohonan.is_submitted
              ? "green darken-2"
              : "red darken-2"
            : "transparent",
        icon: {
          icon:
            beasiswa.tahap != "Tahap Berkas"
              ? permohonan.is_submitted
                ? "mdi-check"
                : "mdi-close"
              : "mdi-calendar-clock",
          color:
            beasiswa.tahap != "Tahap Berkas"
              ? permohonan.is_submitted
                ? "green darken-2"
                : "red darken-2"
              : "grey darken-3",
        },
      });

      if (status) {
        timeline.push({
          kegiatan: "Berkas",
          msg: "Verifikasi berkas",
          is_done: permohonan.is_berkas_passed,
          time: permohonan.verified_at ?? "",
          color:
            permohonan.is_berkas_passed !== null
              ? permohonan.is_berkas_passed
                ? "green darken-2"
                : "red darken-2"
              : "transparent",
          icon: {
            icon:
              permohonan.is_berkas_passed !== null
                ? permohonan.is_berkas_passed
                  ? "mdi-check"
                  : "mdi-close"
                : "mdi-calendar-clock",
            color:
              permohonan.is_berkas_passed !== null
                ? permohonan.is_berkas_passed
                  ? "green darken-2"
                  : "red darken-2"
                : "grey darken-3",
          },
        });
      }
      status = status && permohonan.is_berkas_passed !== 0;

      if (status && permohonan.beasiswa.is_interview) {
        timeline.push({
          kegiatan: "Wawancara",
          msg: "Wawancara",
          is_done: permohonan.is_interview_passed,
          time: permohonan.interviewed_at ?? "",
          color:
            permohonan.is_interview_passed !== null
              ? permohonan.is_interview_passed
                ? "green darken-2"
                : "red darken-2"
              : "transparent",
          icon: {
            icon:
              permohonan.is_interview_passed !== null
                ? permohonan.is_interview_passed
                  ? "mdi-check"
                  : "mdi-close"
                : "mdi-calendar-clock",
            color:
              permohonan.is_interview_passed !== null
                ? permohonan.is_interview_passed
                  ? "green darken-2"
                  : "red darken-2"
                : "grey darken-3",
          },
        });
      }
      status = status && permohonan.is_interview_passed !== 0;

      if (status && permohonan.beasiswa.is_survey) {
        timeline.push({
          kegiatan: "Survey",
          msg: "Survey",
          is_done: permohonan.is_survey_passed,
          time: permohonan.surveyed_at ?? "",
          color:
            permohonan.is_survey_passed !== null
              ? permohonan.is_survey_passed
                ? "green darken-2"
                : "red darken-2"
              : "transparent",
          icon: {
            icon:
              permohonan.is_survey_passed !== null
                ? permohonan.is_survey_passed
                  ? "mdi-check"
                  : "mdi-close"
                : "mdi-calendar-clock",
            color:
              permohonan.is_survey_passed !== null
                ? permohonan.is_survey_passed
                  ? "green darken-2"
                  : "red darken-2"
                : "grey darken-3",
          },
        });
      }
      status = status && permohonan.is_survey_passed !== 0;

      if (status) {
        timeline.push({
          kegiatan: "Kelulusan",
          msg: "Hasil Akhir",
          is_done: permohonan.is_selection_passed,
          time: permohonan.selected_at ?? "",
          color:
            beasiswa.status == "Selesai"
              ? permohonan.is_selection_passed
                ? "green darken-2"
                : "red darken-2"
              : "transparent",
          icon: {
            icon:
              beasiswa.status == "Selesai"
                ? permohonan.is_selection_passed
                  ? "mdi-check"
                  : "mdi-close"
                : "mdi-calendar-clock",
            color:
              beasiswa.status == "Selesai"
                ? permohonan.is_selection_passed
                  ? "green darken-2"
                  : "red-darken-2"
                : "grey darken-3",
          },
        });
      }
      status = permohonan.is_selection_passed;

      this.permohonans["timeline"] = timeline;
      timeline = [];
    },
    cekTahap(beasiswa, tahap) {
      if (tahap == "berkas" && beasiswa.awal_berkas) {
        return `${this.parseDate(beasiswa.awal_berkas)} - ${this.parseDate(
          beasiswa.akhir_berkas
        )}`;
      } else if (beasiswa[`is_${tahap}`]) {
        return `${this.parseDate(beasiswa[`awal_${tahap}`])} - ${this.parseDate(
          beasiswa[`akhir_${tahap}`]
        )}`;
      } else {
        return "-";
      }
    },
    downloadTemplate() {
      const link = "/template/template_kelulusan.xlsx";
      window.open(link, "_blank");
    },
    setKelulusanEditor() {
      this.kuotaTemp = this.selectedBeasiswa.quota;
      this.deletedMHS = [];
      this.insertedMHS = [];
      this.historyStackListMHS.push({
        listMHS: this.selectedBeasiswa[this.filter],
        deletedMHS: this.deletedMHS,
        insertedMHS: this.deletedMHS,
      });
      this.listMHSTemp = _.clone(this.selectedBeasiswa[this.filter]);
      this.listMHS = _.clone(this.selectedBeasiswa[this.filter]);
      let rule = (v) =>
        (v || "") >= this.selectedBeasiswa.quota ||
        `Tidak dapat mengurangi kuota dibawah jumlah yang sudah terdaftar yaitu: ${this.selectedBeasiswa.quota}. Hapus Mahasiswa terlebih dahulu`;
      this.ruleKuota.push(rule);
      this.drawer = true;
    },
    deleteMHS(index, item) {
      console.log();
      console.log("deleting index: ", index);
      this.listMHSTemp.splice(index, 1);
      this.listMHS.splice(index, 1);
      this.deletedMHS = _.clone(this.deletedMHS);
      this.deletedMHS.push(item.id);
      // this.historyStackListMHS.unshift(this.listMHS);
      this.historyStackListMHS.unshift({
        listMHS: _.clone(this.listMHS),
        deletedMHS: _.clone(this.deletedMHS),
        insertedMHS: _.clone(this.insertedMHS),
      });
      console.log("list mhs:", this.listMHSTemp);
    },
    insertMHS() {
      console.log("inserting MHS");
      let item = { mhs_id: this.insertNIM };
      this.listMHSTemp.unshift(item);
      this.listMHS.unshift(item);
      this.insertedMHS = _.clone(this.insertedMHS);
      this.insertedMHS.push(this.insertNIM);
      // this.historyStackListMHS.unshift(this.listMHS);
      this.historyStackListMHS.unshift({
        listMHS: _.clone(this.listMHS),
        deletedMHS: _.clone(this.deletedMHS),
        insertedMHS: _.clone(this.insertedMHS),
      });
      this.insertNIM = null;
      console.log("list mhs:", this.listMHSTemp);
    },
    redo() {
      if (this.historyIndex > 0) {
        this.historyIndex--;
        this.listMHSTemp = _.clone(
          this.historyStackListMHS[this.historyIndex].listMHS
        );
        this.listMHS = _.clone(
          this.historyStackListMHS[this.historyIndex].listMHS
        );
        this.deletedMHS = _.clone(
          this.historyStackListMHS[this.historyIndex].deletedMHS
        );
        this.insertedMHS = _.clone(
          this.historyStackListMHS[this.historyIndex].insertedMHS
        );
        console.log("list mhs:", this.listMHSTemp);
      } else {
        console.log("no more history");
      }
    },
    undo() {
      if (this.historyIndex < this.historyStackListMHS.length - 1) {
        this.historyIndex += 1;
        this.listMHSTemp = _.clone(
          this.historyStackListMHS[this.historyIndex].listMHS
        );
        this.listMHS = _.clone(
          this.historyStackListMHS[this.historyIndex].listMHS
        );
        this.deletedMHS = _.clone(
          this.historyStackListMHS[this.historyIndex].deletedMHS
        );
        this.insertedMHS = _.clone(
          this.historyStackListMHS[this.historyIndex].insertedMHS
        );
        console.log("list mhs:", this.listMHSTemp);
        console.log("history:", this.historyStackListMHS[this.historyIndex]);
      } else {
        console.log("no more history");
      }
    },
    ctrlZ(event) {
      console.log(event);
      if (event.ctrlKey && event.key === "z") {
        console.log("ctrl+z pressed");
        this.undo();
      }
      if (event.ctrlKey && event.key === "Shift" && event.key === "z") {
        console.log("ctrl+shift+z pressed");
        this.redo();
      }
    },
  },
  watch: {
    drawer: function (val) {
      let self = this;
      if (val) {
        window.addEventListener("keyup", this.ctrlZ);
      } else {
        console.log("i should be dead now");
        window.removeEventListener("keyup", this.ctrlZ);
      }
    },
    selectedBeasiswa: function (val) {
      if (val) {
        this.detailBeasiswa = [
          { judul: "Nama", isi: val.nama },
          { judul: "Instansi", isi: val.instansi.name ?? "-" },
          { judul: "Tahap Berkas", isi: this.cekTahap(val, "berkas") },
          { judul: "Tahap Interview", isi: this.cekTahap(val, "interview") },
          { judul: "Tahap Survey", isi: this.cekTahap(val, "survey") },
          { judul: "Kuota", isi: `${val.lulus.length}/${val.quota}` },
          { judul: "Status", isi: val.status },
        ];
      }
    },
    dialog: function (val) {
      if (!val) {
        this.filter = "permohonan";
      }
    },
  },
  computed: {
    ...mapState([
      "beasiswaProgress",
      "isOpenBeasiswa",
      "instansi",
      "isTableLoading",
      "isLoading",
    ]),
    toggleBeasiswa: {
      get: function () {
        return this.isOpenBeasiswa;
      },
      set: function (data) {
        this.toggleOpenBeasiswa(data);
      },
    },
  },
  data() {
    return {
      headersEdit: [
        {
          text: "NIM",
          align: "start",
          value: "mhs_id",
        },
        {
          text: "Nama",
          align: "start",
          value: "mahasiswa.nama",
        },
        { align: "center", text: "Actions", value: "actions", sortable: false },
      ],
      searchEdit: null,
      insertNIM: null,
      historyIndex: 0,
      historyStackDeletedMHS: [],
      historyStackInsertedMHS: [],
      historyStackListMHS: [],
      deletedMHS: [],
      insertedMHS: [],
      listMHS: [],
      listMHSTemp: [],
      ruleKuota: [],
      kuotaTemp: null,
      drawerExcel: false,
      drawer: false,
      permohonans: null,
      beasiswa: null,
      item: null,
      dialogMHS: false,
      btnLoading: false,
      selectedBeasiswa: "",
      file: "",
      pemohon: [],
      lulus: [],
      form: {},
      dialog: false,
      filter: "permohonan",
      tab: null,
      tabs: ["permohonan", "lulus"],
      detailBeasiswa: {},
      snackbar: { show: false },
      search: {
        beasiswa: "",
        permohonan: "",
      },
      headers: {
        detailBeasiswa: [
          { text: "Judul", value: "judul" },
          { text: "Isi", value: "isi" },
        ],
        beasiswa: [
          {
            text: "Beasiswa",
            align: "start",
            sortable: false,
            value: "nama",
          },
          { text: "Instansi", value: "instansi.name" },
          { text: "Status", value: "status" },
        ],
        pemohon: [
          {
            text: "Nama",
            align: "start",
            sortable: false,
            value: "mahasiswa.nama",
          },
          { text: "NIM", value: "mhs_id" },
          { text: "Berkas", value: "verificator" },
          { text: "Interview", value: "interviewer" },
          { text: "Survey", value: "surveyor" },
          { text: "Lulus", value: "selector" },
        ],
      },
    };
  },
};
</script>

<style scoped>
.row-pointer >>> tbody tr :hover {
  cursor: pointer;
}
.fab {
  color: #2e7d323b;
  position: fixed;
  bottom: 20px;
  right: 20px;
}
.floating {
  width: 100%;
  position: fixed;
  bottom: 20px;
  z-index: 10;
}
</style>
