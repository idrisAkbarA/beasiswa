<template>
  <v-container fill-height>
    <v-row
      justify="center"
      v-if="permohonans.length<1"
    >
      <h2>Tidak ada permohonan</h2>
    </v-row>
    <v-row
      justify="center"
      v-for="(item,index) in permohonans"
      :key="index"
    >
      <v-card :class="checkColor(item)">
        <v-card-title>
          {{item.beasiswa.nama}}
        </v-card-title>
        <v-card-subtitle>
          <span class="title">
            Status: <strong>
              {{checkStatus(item)}} </strong>

          </span>
        </v-card-subtitle>
        <v-divider></v-divider>
        <v-card-text>
          <v-row>
            <v-col cols="12">
              <v-simple-table class="transparent">
                <template v-slot:default>
                  <thead>
                    <tr>
                      <th class="text-left">
                        Kegiatan
                      </th>
                      <th class="text-left">
                        Jadwal
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Pengisian Berkas</td>
                      <td><span v-if="item.beasiswa.awal_berkas"><strong>{{parseDate(item.beasiswa.awal_berkas)}}</strong> sampai </span> <strong> {{parseDate(item.beasiswa.akhir_berkas)}}</strong></td>
                    </tr>
                    <tr v-if="item.beasiswa.is_interview">
                      <td>Wawancara</td>
                      <td><span><span v-if="item.beasiswa.awal_interview"><strong>{{parseDate(item.beasiswa.awal_interview)}} </strong>sampai </span> <strong>{{parseDate(item.beasiswa.akhir_interview)}}</strong> </span></td>
                    </tr>
                    <tr v-if="item.beasiswa.is_survey">
                      <td>Survey</td>
                      <td><span><strong>{{parseDate(item.beasiswa.awal_survey)}}</strong> sampai </span> <strong>{{parseDate(item.beasiswa.akhir_survey)}} </strong> </td>
                    </tr>
                  </tbody>
                </template>
              </v-simple-table>

            </v-col>
          </v-row>
        </v-card-text>
      </v-card>
    </v-row>
  </v-container>
</template>

<script>
import { mapState, mapActions } from "vuex";
export default {
  created() {
    this.getUserPermohonan();
  },
  methods: {
    isShowTimeline(item) {
      if (item.is_selection_passed == 0) {
        return false;
      }
      if (item.is_selection_passed == 1) {
        return true;
      }
      if (item.is_berkas_passed == 0) {
        return false;
      }
      if (item.is_interview_passed == 0) {
        return false;
      }
      if (item.is_survey_passed == 0) {
        return false;
      }
      return true;
    },
    checkStatus(item) {
      if (item.beasiswa.deleted_at) {
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
      } else if (item.is_submitted) {
        return "Permohonan anda sedang di proses";
      } else {
        return "Permohonan belum di proses, mohon lengkapi persyaratan";
      }
    },
    checkColor(item) {
      if (item.beasiswa.deleted_at) {
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
      } else {
        return "#2e7d323b";
      }
    },
    parseDate(date) {
      return this.$moment(date, "YYYY-MM-DD").format("Do MMMM YYYY");
    },
    getUserPermohonan() {
      axios.get("/api/pemohon/cek-isHas").then(response => {
        console.log(response.data);
        this.permohonans = response.data;
        this.addTimeline(response.data);
      });
    },
    addTimeline(permohonans) {
      var timeline = [];
      permohonans.forEach((element, index) => {
        timeline.push({
          kegiatan: "Pengisian Berkas",
          awal_tgl: element.beasiswa.awal_berkas,
          akhir_tgl: element.beasiswa.akhir_berkas,
          msg: "Pengisian berkas",
          is_done: true
        });
        if (element.beasiswa.is_interview == 1) {
          var is_done = false;
          if (element.is_interview_passed == 1) {
            is_done = true;
          }
          timeline.push({
            kegiatan: "Wawancara",
            awal_tgl: element.beasiswa.awal_interview,
            akhir_tgl: element.beasiswa.akhir_interview,
            is_done,
            msg: `Lakukan wawancara pada tanggal
            ${
              element.beasiswa.awal_interview
                ? this.parseDate(element.beasiswa.awal_interview) + " sampai "
                : ""
            }  ${this.parseDate(element.beasiswa.akhir_interview)}`
          });
        }
        if (element.beasiswa.is_survey == 1) {
          var is_done = false;
          if (element.is_survey_passed == 1) {
            is_done = true;
          }
          timeline.push({
            kegiatan: "Wawancara",
            awal_tgl: element.beasiswa.awal_survey,
            akhir_tgl: element.beasiswa.akhir_survey,
            is_done,
            msg: `Team survey akan melakukan survey pada
            ${
              element.beasiswa.awal_survey
                ? this.parseDate(element.beasiswa.awal_survey) + " sampai "
                : ""
            }  ${this.parseDate(
              element.beasiswa.akhir_survey
            )}, harap menunggu.`
          });
        }
        var is_done = false;
        if (element.is_selection_passed) {
          is_done = true;
        }
        timeline.push({
          kegiatan: "Seleksi pimpinan",
          awal_tgl: null,
          akhir_tgl: null,
          is_done,
          msg: "Harap menunggu hasil seleksi pimpinan."
        });
        permohonans[index]["timeline"] = timeline;
        timeline = [];
      });
    }
  },
  data() {
    return {
      permohonans: [],
      color: ""
    };
  }
};
</script>

<style scoped>
.diproses {
  background: #2e7d323b;
}
.ditolak {
  background: rgb(241, 34, 31);
  background: linear-gradient(
    180deg,
    rgba(241, 34, 31, 0.5885951792826505) 0%,
    rgba(251, 187, 114, 0.17122823250393904) 100%
  );
}
.diterima {
  background: rgb(4, 131, 17);
  background: linear-gradient(
    180deg,
    rgba(4, 131, 17, 0.5885951792826505) 0%,
    rgba(114, 251, 141, 0.17122823250393904) 100%
  );
}
</style>
