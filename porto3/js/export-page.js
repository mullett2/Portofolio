document.addEventListener("DOMContentLoaded", function () {
  const exportBtn = document.getElementById("exportCurrentPage");
  if (!exportBtn) return;

  exportBtn.addEventListener("click", function () {
    const table = document.getElementById("dataTable");
    if (!table) return;

    // Ambil hanya baris yang kelihatan
    const visibleRows = Array.from(table.querySelectorAll("tbody tr")).filter(row => {
      return row.offsetParent !== null;
    });

    // Buat tabel sementara
    const tempTable = document.createElement("table");
    const theadClone = table.querySelector("thead").cloneNode(true);
    const tbodyClone = document.createElement("tbody");

    visibleRows.forEach(row => {
      tbodyClone.appendChild(row.cloneNode(true));
    });

    tempTable.appendChild(theadClone);
    tempTable.appendChild(tbodyClone);

    // Konversi ke worksheet
    const worksheet = XLSX.utils.table_to_sheet(tempTable);
    const workbook = XLSX.utils.book_new();

    // Tambahkan tanda tangan di bawah data
    const range = XLSX.utils.decode_range(worksheet['!ref']);
    let lastRow = range.e.r + 6;

    worksheet[`A${lastRow}`] = { t: "s", v: "Dibuat oleh," };
    worksheet[`E${lastRow}`] = { t: "s", v: "Mengetahui," };

    lastRow += 4; // Spasi untuk tanda tangan

    worksheet[`A${lastRow}`] = { t: "s", v: ".................." };
    worksheet[`E${lastRow}`] = { t: "s", v: ".................." };

    // Perbarui area range agar semua data masuk
    worksheet['!ref'] = XLSX.utils.encode_range({
      s: { c: 0, r: 0 },
      e: { c: range.e.c, r: lastRow }
    });

    // Tambahkan ke workbook dan export ke file
    XLSX.utils.book_append_sheet(workbook, worksheet, "Current Page");
    XLSX.writeFile(workbook, "Laporan_Data_Harian.xlsx");
  });
});
