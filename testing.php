<div class="drawer">
  <h2>Update Stock</h2>
  <form>
    <label>Action</label>
    <div>
      <input type="radio" name="action" value="add"> Add
      <input type="radio" name="action" value="subtract"> Subtract
    </div>

    <label>Quantity</label>
    <input type="number" min="1" required>

    <label>Client</label>
    <select>
      <option>Client A</option>
      <option>Client B</option>
    </select>

    <label>Date Delivered</label>
    <input type="date" value="<?php echo date('Y-m-d'); ?>">

    <label>Remarks</label>
    <textarea placeholder="Optional notes..."></textarea>

    <button type="submit">Save</button>
  </form>
</div>
