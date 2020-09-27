<template>
     <v-skeleton-loader
      type="table"
      :loading="isTableLoading"
      transition="fade-transition"
    >

      <v-data-table
        :headers="headers"
        :items="beasiswa"
        :items-per-page="10"
        style="background-color: #2e7d323b"
        class="elevation-10 mb-10"
      >
        <template v-slot:item.actions="{ item }">
          <v-icon
            small
            class="mr-2"
            @click="editItem(item)"
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
        <template v-slot:no-data>
          no data
        </template>
      </v-data-table>
    </v-skeleton-loader>
</template>

<script>
import { mapActions, mapMutations, mapState } from "vuex";
export default {

}
</script>

<style>

</style>