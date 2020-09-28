<template>
  <v-container fluid>
    <v-skeleton-loader
      :loading="isTableLoading"
      type="table"
      transition="fade-transition"
    >
      <v-data-table
        :headers="headers"
        :items="akunPetugas"
        :items-per-page="10"
        style="background-color: #2e7d323b"
        class="elevation-10 mb-10"
      >
        <template v-slot:item.actions="{ item }">
          <v-icon
            small
            class="mr-2"
            @click="edit(item)"
          >
            mdi-pencil
          </v-icon>
          <v-icon
            small
            @click="deleteItem(item)"
          >
            mdi-delete
          </v-icon>
        </template>
        <template v-slot:item.role="{ item }">
          <v-chip v-if="item.role==1" color="red">
            {{ convertRole(item.role) }}
          </v-chip>
          <v-chip v-if="item.role==2" color="green">
            {{ convertRole(item.role) }}
          </v-chip>
          <v-chip v-if="item.role==3" color="blue">
            {{ convertRole(item.role) }}
          </v-chip>
          <v-chip v-if="item.role==4" color="orange">
            {{ convertRole(item.role) }}
          </v-chip>
        </template>
        <template v-slot:no-data>
          no data
        </template>
      </v-data-table>
    </v-skeleton-loader>
    <v-bottom-sheet
      scrollable
      width="60%"
      inset
      overlay-color="#69F0AE"
      v-model="toggleEdit"
    >
      <v-card>
        <v-card-title> <span>Edit Petugas</span>
          <v-spacer></v-spacer>
          <v-btn
            @click="tidakLulusButton"
            text
          >Batal</v-btn>
          <v-btn
            color="#2E7D32"
            :loading="btnLoading"
            @click="storeEdit"
          >Simpan</v-btn>
        </v-card-title>
        <v-card-text>
          <v-row class="ma-5">
            <v-col cols="4" xs="12">
              <v-text-field
                color="white"
                label="Username"
                v-model="editItem.name"
              >
              </v-text-field>
            </v-col>
            <v-col cols="4" xs="12">
              <v-select
                label="Role"
                color="white"
                :items="role"
                item-text="role"
                item-value="id"
                v-model="editItem.role"
              ></v-select>
            </v-col>
            <v-col cols="4" xs="12">
                <v-text-field
                color="white"
                label="Password"
                type="password"
                hint="Isi untuk ganti password"
                persistent-hint
                v-model="editItem.password"
                >

                </v-text-field>
            </v-col>
          </v-row>

        </v-card-text>
      </v-card>
    </v-bottom-sheet>

    <v-bottom-sheet
      scrollable
      width="60%"
      inset
      overlay-color="#69F0AE"
      v-model="toggleAkunPetugas"
    >
      <v-card>
        <v-card-title> <span>Tambah Petugas</span>
          <v-spacer></v-spacer>
          <v-btn
            @click="tidakLulusButton"
            text
          >Batal</v-btn>
          <v-btn
            color="#2E7D32"
            :loading="btnLoading"
            v-model="openSheet"
            @click="store()"
          >Tambah Petugas</v-btn>
        </v-card-title>
        <v-card-text>
          <v-row class="ma-5">
            <v-col cols="4" xs="12">
              <v-text-field
                color="white"
                label="Username"
                v-model="storeItem.name"
              >
              </v-text-field>
            </v-col>
            <v-col cols="4" xs="12">
              <v-select
                label="Role"
                color="white"
                :items="role"
                item-text="role"
                item-value="id"
                v-model="storeItem.role"
              ></v-select>
            </v-col>
            <v-col cols="4" xs="12">
                <v-text-field
                color="white"
                label="Password"
                type="password"
                v-model="storeItem.password"
                >

                </v-text-field>
            </v-col>
          </v-row>

        </v-card-text>
      </v-card>
    </v-bottom-sheet>
    <v-dialog
      width="400"
      overlay-color="#69F0AE"
      v-model="dialog"
    >
      <v-card>
        <v-card-title class="mt-2">
          {{msg}}
          <p style="font-weight:bold">
            <!-- {{itemtoDelete.nama}}? -->
          </p>
        </v-card-title>
        <v-card-actions>
          <v-btn
            text
            @click="dialog = false"
          >
            Batal
          </v-btn>
          <v-spacer></v-spacer>
          <v-btn
            dark
            class="green"
          >
            <v-icon left>mdi-check</v-icon>
            iya
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script>
import { mapActions, mapMutations, mapState } from "vuex";
export default {
  created() {
    this.getAkunPetugas();
  },
  computed: {
    ...mapState([
      "nim",
      "url",
      "isOpenBeasiswa",
      "isTableLoading",
      "akunPetugas"
    ]),
    // ...mapState(["beasiswa", "isOpenBeasiswa", "instansi","isTableLoading","isLoading"]),
    toggleAkunPetugas: {
      get: function() {
        return this.isOpenBeasiswa;
      },
      set: function(data) {
        this.toggleOpenBeasiswa(data);
      }
    }
  },
  methods: {
    ...mapMutations(["toggleOpenBeasiswa"]),
    ...mapActions(["getAkunPetugas","storeAkunPetugas","editAkunPetugas"]),
    edit(item){
        this.toggleEdit = true;
        this.editItem.name = item.name;
        this.editItem.role = item.role;
        this.editItem.id = item.id;
    },
    storeEdit(){
        var data={
            name: this.editItem.name,
            password: this.editItem.password,
            role: this.editItem.role
        }
        this.editAkunPetugas(this.editItem).then(response=>{
            console.log(response.data)
        })
    },
    store(){
        console.log(this.storeItem)
        this.storeAkunPetugas(this.storeItem).then(response=>{
            this.storeItem = {};
        })
    },
    convertRole(role) {
      if (role == 1) {
        return "Administrator";
      } else if (role == 2) {
        return "Pewawancara";
      } else if (role == 3) {
        return "Surveyor";
      } else if (role == 4) {
        return "Petinggi";
      }
    }
  },
  data() {
    return {
        editItem:{
            name:"",
            role:"",
            password:""
        },
        storeItem:{
            name:"",
            role:"",
            password:""
        },
        role:[
            {id:1,role:"Administrator"},
            {id:2,role:"Pewawancara"},
            {id:3,role:"Surveyor"},
            {id:4,role:"Petinggi"},
        ],
        toggleEdit:false,
      sheetMsg: "",
      lulusButton: false,
      tidakLulusButton: false,
      opensheet: false,
      dialog: false,
      msg: "",
      rincian: {},
      btnLoading: false,
      openSheet: false,
      headers: [
        {
          text: "Username",
          align: "start",
          sortable: false,
          value: "name"
        },
        { text: "Role", value: "role" },
        { text: "Actions", value: "actions", sortable: false }
      ]
    };
  }
};
</script>

<style>
</style>