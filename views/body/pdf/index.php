
    <h1 class="app-page-title">Rekap Laporan</h1>

    <div style="max-width: 100%; padding: 20px; background-color: #ffffff; border-radius: 10px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);">
        <form action="views/body/pdf/pdf.php?" method="get" style="display: flex; flex-wrap: wrap; gap: 20px; align-items: center;">
            <div style="flex: 1 1 100%; max-width: 100%;">
                <label for="daritanggal" style="font-weight: bold;">Dari Tanggal:</label>
                <input type="date" id="daritanggal" name="daritanggal" required style="width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 5px;">
            </div>
            <div style="flex: 1 1 100%; max-width: 100%;">
                <label for="sampaitanggal" style="font-weight: bold;">Sampai Tanggal:</label>
                <input type="date" id="sampaitanggal" name="sampaitanggal" required style="width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 5px;">
            </div>
            <div style="flex: 1 1 100%; max-width: 100%; text-align: center;">
                <button type="submit" style="padding: 10px 20px; background-color: #15a362; color: #fff; border: none; border-radius: 5px; cursor: pointer;">Cetak</button>
            </div>
        </form>
    </div>
